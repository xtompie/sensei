<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Kernel\AppDir;

class SetupCommand
{
    public static function command(): Command
    {
        return new Command(name: 'app:env:setup', command: self::class);
    }

    public function __construct(
        private Output $output,
        private AppDir $appDir,
        private Env $env,
    ) {
    }

    public function __invoke(): int
    {
        $file = $this->appDir->__invoke() . '/.env';

        if (is_file($file)) {
            $this->output->errorln('File already exists: ' . $file);
            return 1;
        }

        foreach ($this->env->entries() as $entry) {
            file_put_contents($file, "{$entry->key()}={$entry->default()}\n", FILE_APPEND);
        }

        $this->output->infoln('File created: ' . $file);

        return 0;
    }
}
