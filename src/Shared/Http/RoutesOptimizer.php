<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Optimize\Optimizer;

class RoutesOptimizer implements Optimizer
{
    public function __construct(
        private Routes $routes,
    ) {
    }

    public function optimize(): void
    {
        $this->routes->optimize();
    }
}
