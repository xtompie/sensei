<?php

declare(strict_types=1);

namespace App\Sentry\Application\Service\Sentry;

use App\Sentry\Infrastructure\VoterDiscoverOptimizer;

class Sentry
{
    public function __construct(
        private VoterDiscoverOptimizer $voterDiscoverOptimizer,
    ) {
    }

    public function __invoke(string $sid): bool
    {
        foreach ($this->voterDiscoverOptimizer->instances() as $voter) {
            if ($voter->__invoke($sid)) {
                return true;
            }
        }
        return false;
    }
}
