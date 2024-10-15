<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Shared\Kernel\DiscoverOptimizer;

/**
 * @extends DiscoverOptimizer<Pilot>
 */
class PilotDiscoveryOptimizer extends DiscoverOptimizer
{
    protected function instanceof(): string
    {
        return Pilot::class;
    }

    protected function suffix(): string
    {
        return 'Pilot';
    }
}
