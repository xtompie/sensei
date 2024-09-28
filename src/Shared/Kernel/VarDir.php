<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

final class VarDir
{
    public function __construct(
        private AppDir $appDir,
    ) {
    }

    public function __invoke(): string
    {
        return ($this->appDir)() . '/var';
    }
}
