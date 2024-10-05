<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;
use App\Shared\Kernel\AppDir;

#[Name('app:env:setup')]
#[Description('Setup environment variables')]
class SetupCommand implements Command
{
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
