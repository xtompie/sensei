<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

final class CacheDir
{
    public function __construct(
        private AppDir $appDir,
    ) {
    }

    public function __invoke(): string
    {
        return ($this->appDir)() . '/var/cache';
    }
}
