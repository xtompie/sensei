<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Kernel\Discover;

class Optimize
{
    public function __construct(
        private Discover $discover,
    ) {
    }

    public function __invoke(): void
    {
        foreach ($this->discover->instances(instanceof: Optimizer::class, suffix: 'Optimizer') as $optimize) {
            $optimize->optimize();
        }
    }
}
