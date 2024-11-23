<?php

declare(strict_types=1);

namespace App\Sentry\System;

class Sentry
{
    public function __construct(
        private VoterDiscoverer $voterDiscoverer,
    ) {
    }

    public function __invoke(Rid $rid): bool
    {
        foreach ($this->voterDiscoverer->instances() as $voter) {
            if ($voter->__invoke($rid)) {
                return true;
            }
        }

        return false;
    }
}
