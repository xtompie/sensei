<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Shared\Kernel\DiscoverOptimizer;

/**
 * @extends DiscoverOptimizer<Repository>
 */
class RepositoryDiscoveryOptimizer extends DiscoverOptimizer
{
    protected function instanceof(): string
    {
        return Repository::class;
    }

    protected function suffix(): string
    {
        return 'Repository';
    }
}
