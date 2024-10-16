<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<Command>
 */
class CommandDiscoverer extends Discoverer
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
