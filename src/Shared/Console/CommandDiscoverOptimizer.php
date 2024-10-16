<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Kernel\DiscoverOptimizer;

/**
 * @extends DiscoverOptimizer<Command>
 */
class CommandDiscoverOptimizer extends DiscoverOptimizer
{
    protected function instanceof(): string
    {
        return Command::class;
    }

    protected function suffix(): string
    {
        return 'Command';
    }
}
