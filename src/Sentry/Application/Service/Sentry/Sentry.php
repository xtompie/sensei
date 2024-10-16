<?php

declare(strict_types=1);

namespace App\Sentry\Application\Service\Sentry;

use App\Sentry\Infrastructure\VoterDiscoverer;

class Sentry
{
    public function __construct(
        private VoterDiscoverer $voterDiscoverer,
    ) {
    }

    public function __invoke(string $sid): bool
    {
        foreach ($this->voterDiscoverer->instances() as $voter) {
            if ($voter->__invoke($sid)) {
                return true;
            }
        }
        return false;
    }
}
