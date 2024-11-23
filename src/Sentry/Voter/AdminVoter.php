<?php

declare(strict_types=1);

namespace App\Sentry\Voter;

use App\Sentry\Rid\BackendResourceRid;
use App\Sentry\Rid\MediaUploadRid;
use App\Sentry\System\Rid;
use App\Sentry\System\Role;
use App\Sentry\System\RoleContext;
use App\Sentry\System\Voter;

class AdminVoter implements Voter
{
    public function __construct(
        private RoleContext $roleContext,
    ) {
    }

    public function __invoke(Rid $rid): bool
    {
        if ($this->roleContext->get() !== Role::ADMIN) {
            return false;
        }

        if ($rid instanceof BackendResourceRid) {
            return true;
        }

        if ($rid instanceof MediaUploadRid) {
            return true;
        }

        return false;
    }
}
