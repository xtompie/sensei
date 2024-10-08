<?php

declare(strict_types=1);

namespace App\Sentry\Application\Voter;

use App\Sentry\Application\Model\Role;
use App\Sentry\Application\Model\RoleContext;
use App\Sentry\Application\Model\Voter;

class TenantVoter implements Voter
{
    public function __construct(
        protected RoleContext $roleContext,
    ) {
    }

    public function __invoke(string $resource): bool
    {
        if (!str_starts_with($resource, 'tenant.id.')) {
            return false;
        }

        if ($this->roleContext->get()->equals(Role::superadmin())) {
            return true;
        }

        $id = substr($resource, strlen('tenant.id.'));

        if ($id === '') {
            return false;
        }

        return false;
    }
}
