<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Kernel\AppDir;
use App\Shared\Kernel\Dir;

final class OptimizeDir
{
    public function __construct(
        private AppDir $appDir,
        private ?string $dir = null,
    ) {
    }

    public function __invoke(): string
    {
        if (!$this->dir) {
            $this->dir = $this->appDir->__invoke() . '/optimize';
            Dir::ensure($this->dir);
        }
        return $this->dir;
    }
}
