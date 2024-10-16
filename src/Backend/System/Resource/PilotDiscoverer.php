<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<Pilot>
 */
class PilotDiscoverer extends Discoverer
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
