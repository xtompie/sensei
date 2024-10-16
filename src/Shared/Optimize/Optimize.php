<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Kernel\Source;
use Generator;

class Optimize
{
    public function __construct(
        private Source $source,
    ) {
    }

    public function __invoke(): void
    {
        foreach ($this->optimizers() as $optimizer) {
            $optimizer->optimize();
        }
    }

    /**
     * @return Generator<int, Optimizer>
     */
    private function optimizers(): Generator
    {
        yield from $this->source->instances(instanceof: Optimizer::class, suffix: 'Optimizer');
        yield from $this->source->instances(instanceof: Optimizer::class, suffix: 'Discoverer');
    }
}
