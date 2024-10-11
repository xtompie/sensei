<?php

declare(strict_types=1);

namespace App\Sentry\Infrastructure;

use App\Sentry\Application\Model\Voter;
use App\Shared\Kernel\DiscoverOptimizer;

/**
 * @extends DiscoverOptimizer<Voter>
 */
class VoterDiscoverOptimizer extends DiscoverOptimizer
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
