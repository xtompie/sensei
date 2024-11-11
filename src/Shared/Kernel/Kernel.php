<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

use App\Shared\Env\Env;
use App\Shared\Http\Response;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

class Kernel
{
    public function __construct(
        private AppDir $appDir,
        private Debug $debug,
        private Env $env,
    ) {
    }

    public function __invoke(string $appDir): void
    {
        $this->appDir->set($appDir);

        $debug = $this->debug->__invoke();
        $cli = php_sapi_name() === 'cli';

        ini_set('log_errors', '1');
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
        }

        set_error_handler(function ($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) {
                return false;
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        if ($debug) {
            if (!$cli) {
                (new \Whoops\Run())
                    ->addFrameFilter(function ($frame) {
                        $function = $frame->getFunction();
                        $file = $frame->getFile();
                        $class = $frame->getClass();
                        if ($function == 'include' && str_ends_with($file, '/vendor/xtompie/tpl/src/Tpl.php')) {
                            return null;
                        }
                        if ($class == 'Xtompie\Tpl\Tpl' && $function == 'Xtompie\Tpl\{closure}') {
                            return null;
                        }
                        if ($class == 'Xtompie\Tpl\Tpl' && $function == 'render') {
                            return null;
                        }
                        if ($class == 'Whoops\Run' && $function == 'handleError') {
                            return null;
                        }
                        return $frame;
                    })
                    ->pushHandler(
                        (new \Whoops\Handler\PrettyPageHandler())
                        ->setEditor($this->env->APP_KERNEL_WHOOPS_EDITOR())
                    )
                    ->register()
                ;
            }
        } else {
            if (!$cli) {
                set_exception_handler(function ($exception) {
                    error_log(
                        'Uncaught exception: ' . $exception->getMessage() . ' ' .
                        'in ' . $exception->getFile() . ':' . $exception->getLine() . ' ' .
                        "Stack trace:\n" . $exception->getTraceAsString()
                    );
                    (new SapiEmitter())->emit(Response::internalServerError());
                });
            }
        }
    }
}
