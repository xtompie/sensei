<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Http\Routes;

class Optimize
{
    public function __construct(
        private Routes $routes,
    ) {
    }

    public function __invoke(): void
    {
        $this->routes->optimize();
    }
}
