<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<SymfonyCommandProvider>
 */
class SymfonyCommandProviderDiscoverer extends Discoverer
{
    protected function instanceof(): string
    {
        return SymfonyCommandProvider::class;
    }

    protected function suffix(): string
    {
        return 'SymfonyCommandProvider';
    }
}
