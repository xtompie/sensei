<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Pilot;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<ResourcePilot>
 */
class ResourcePilotDiscoverer extends Discoverer
{
    protected function instanceof(): string
    {
        return ResourcePilot::class;
    }

    protected function suffix(): string
    {
        return 'Pilot';
    }
}
