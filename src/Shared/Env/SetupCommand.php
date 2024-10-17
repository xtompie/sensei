<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;
use App\Shared\Kernel\AppDir;

#[Name('app:env:setup')]
#[Description('Create .env file with default values')]
class SetupCommand implements Command
{
    public function __invoke(Output $output, AppDir $appDir, Env $env): int
    {
        $file = $appDir->__invoke() . '/.env';

        if (is_file($file)) {
            $output->errorln('File already exists: ' . $file);
            return 1;
        }

        foreach ($env->entries() as $entry) {
            file_put_contents($file, "{$entry->key()}={$entry->default()}\n", FILE_APPEND);
        }

        $output->infoln('File created: ' . $file);

        return 0;
    }
}
