<?php

declare(strict_types=1);

namespace App\Sentry\Application\Model;

class RoleContext
{
    public function __construct(
        private ?Role $role = null,
    ) {
    }

    public function set(Role $role): void
    {
        $this->role = $role;
    }

    public function get(): Role
    {
        if ($this->role === null) {
            throw new \RuntimeException('Role not set');
        }
        return $this->role;
    }

    public function equals(Role $role): bool
    {
        return $this->get()->equals($role);
    }
}
