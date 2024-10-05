<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Http\Routes;
use App\Shared\Kernel\Dir;

class Optimize
{
    public function __construct(
        private Routes $routes,
        private OptimizeDir $optimizeDir,
    ) {
    }

    public function __invoke(): void
    {
        Dir::ensure($this->optimizeDir->__invoke());
        $this->routes->optimize();
    }
}
