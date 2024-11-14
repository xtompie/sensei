<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<ResourceRepository>
 */
class RepositoryDiscoverer extends Discoverer
{
    protected function instanceof(): string
    {
        return ResourceRepository::class;
    }

    protected function suffix(): string
    {
        return 'Repository';
    }
}
