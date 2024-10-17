<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Kernel\AppDir;

final class OptimizeDir
{
    public function __construct(
        private AppDir $appDir,
    ) {
    }

    public function __invoke(): string
    {
        return $this->appDir->__invoke() . '/optimize';
    }
}
