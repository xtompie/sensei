<?php

declare(strict_types=1);

namespace App\Sentry\Infrastructure;

use App\Sentry\Application\Model\Voter;
use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<Voter>
 */
class VoterDiscoverer extends Discoverer
{
    protected function instanceof(): string
    {
        return Voter::class;
    }

    protected function suffix(): string
    {
        return 'Voter';
    }
}
