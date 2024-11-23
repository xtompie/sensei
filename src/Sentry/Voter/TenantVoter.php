<?php

declare(strict_types=1);

namespace App\Sentry\Voter;

use App\Sentry\System\Rid;
use App\Sentry\System\Role;
use App\Sentry\System\RoleContext;
use App\Sentry\System\Voter;

class TenantVoter implements Voter
{
    public function __construct(
        protected RoleContext $roleContext,
    ) {
    }

    public function __invoke(Rid $rid): bool
    {
        if ($this->roleContext->get() === Role::SUPERADMIN) {
            return true;
        }

        return false;
    }
}
