<?php

declare(strict_types=1);

namespace App\Backend\System\Role;

use App\Sentry\System\Role;
use App\Sentry\System\RoleCollection;

class ListRole
{
    public function __invoke(): RoleCollection
    {
        return new RoleCollection([
            Role::ADMIN,
            Role::SUPERADMIN,
        ]);
    }
}
