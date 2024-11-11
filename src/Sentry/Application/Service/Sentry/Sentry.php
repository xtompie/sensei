<?php

declare(strict_types=1);

namespace App\Sentry\Application\Service\Sentry;

use App\Sentry\Application\Model\Role;
use App\Sentry\Application\Model\RoleContext;
use App\Sentry\Infrastructure\VoterDiscoverer;
use App\Shared\Container\Container;

class Sentry
{
    public function __construct(
        private VoterDiscoverer $voterDiscoverer,
    ) {
    }

    public function __invoke(string $sid): bool
    {
        // @TEST
        Container::container()->get(RoleContext::class)->set(Role::superadmin());
        foreach ($this->voterDiscoverer->instances() as $voter) {
            if ($voter->__invoke($sid)) {
                return true;
            }
        }
        return false;
    }
}
