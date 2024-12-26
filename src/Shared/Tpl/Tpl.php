<?php

declare(strict_types=1);

namespace App\Shared\Tpl;

use App\Sentry\System\Rid;
use App\Shared\Container\Container;
use App\Shared\Http\Csrf;
use App\Shared\Http\Request;
use App\Shared\Http\Url;
use App\Shared\I18n\Translator;
use App\Shared\Kernel\AppDir;
use App\Shared\Kernel\Debug;
use Throwable;

final class Tpl
{
    private string $dir;
    /** @var array<string,bool> */
    private array $import = [];
    /** @var array<int,array{template:string,data:array<string,mixed>}> */
    private array $stack = [];
    private string $content = '';

    public function __construct(
        private AppDir $appDir,
        private Csrf $csrf,
        private Debug $debug,
        private Request $request,
        private Translator $translator,
        private Url $url,
    ) {
        $this->dir = $this->appDir->__invoke();
    }

    /**
     * @param array<string,mixed> $data
     */
    public function __invoke(string $template, array $data = []): string
    {
        $this->wrap($template, $data);
        while ($this->stack) {
            $level = array_pop($this->stack);
            $this->content = $this->render($level['template'], $level['data']);
        }

        return $this->content;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function wrap(string $template, array $data = []): void
    {
        $this->stack[] = ['template' => $template, 'data' => $data];
    }

    public function content(): string
    {
        return $this->content;
    }

    public function raw(string|int|float|null $value): string
    {
        return (string) $value;
    }

    public function e(string|int|float|null $value): string
    {
        return htmlspecialchars((string) $value);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function _render(string $template, array $data = []): string
    {
        $path = $this->dir . $template;
        $level = ob_get_level();
        ob_start();
        try {
            (function () { // @phpstan-ignore-line
                extract(func_get_arg(1)); // @phpstan-ignore-line
                include func_get_arg(0);
            })($path, $data);
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean(); // @phpstan-ignore-line
    }

    /**
     * @param array<string,mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        if ($this->debug->__invoke()) {
            return "\n<!-- #tpl: $template  -->\n"
                . $this->_render($template, $data)
                . "\n<!-- /tpl: $template -->\n"
            ;
        }

        return $this->_render($template, $data);
    }

    /**
     * @template T of object
     * @param class-string<T> $service
     * @return T
     */
    public function service(string $service): object
    {
        return Container::container()->get($service);
    }

    public function import(string $template): string
    {
        if (isset($this->import[$template])) {
            return '';
        }
        $this->import[$template] = true;

        return $this->render($template);
    }

    public function sentry(Rid $rid): bool
    {
        return $this->service(\App\Sentry\System\Sentry::class)->__invoke($rid);
    }

    public function csrf(): string
    {
        return $this->csrf->get();
    }

    /**
     * @param array<string,mixed> $query
     */
    public function alterUri(array $query): string
    {
        return $this->request->alterUri($query);
    }

    /**
     * @param array<string,string> $replacements
     */
    public function t(string $key, array $replacements = []): string
    {
        return $this->translator->__invoke($key, $replacements);
    }

    public function isUriAciive(string $url): bool
    {
        return str_starts_with($this->request->getUri()->getPath(), $url);
    }

    /**
     * @param class-string $controller
     * @param array<string,mixed> $parameters
     */
    public function url(string $controller, array $parameters = []): string
    {
        return $this->url->__invoke(controller: $controller, parameters: $parameters);
    }
}
