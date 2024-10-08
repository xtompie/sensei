<?php

declare(strict_types=1);

namespace App\Sentry\Application\Voter;

use App\Sentry\Application\Model\Role;
use App\Sentry\Application\Model\RoleContext;
use App\Sentry\Application\Model\Voter;

class SuperadminVoter implements Voter
{
    public function __construct(
        protected RoleContext $roleContext,
    ) {
    }

    public function __invoke(string $resource): bool
    {
        if (!$this->roleContext->equals(Role::superadmin())) {
            return false;
        }

        return true;
    }
}
