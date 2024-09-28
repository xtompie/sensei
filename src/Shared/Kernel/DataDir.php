<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

final class DataDir
{
    public function __construct(
        private AppDir $appDir,
    ) {
    }

    public function __invoke(): string
    {
        return ($this->appDir)() . '/var/data';
    }
}
