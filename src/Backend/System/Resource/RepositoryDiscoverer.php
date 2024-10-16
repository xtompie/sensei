<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<Repository>
 */
class RepositoryDiscoverer extends Discoverer
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
