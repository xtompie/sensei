<?php

declare(strict_types=1);

namespace App\Sentry\System;

use Xtompie\Container\Container;
use Xtompie\Container\Provider;

final class RoleContext implements Provider
{
    public static function provide(string $abstract, Container $container): object
    {
        return new static(Role::GUEST);
    }

    public function __construct(
        private Role $role,
    ) {
    }

    public function set(Role $role): void
    {
        $this->role = $role;
    }

    public function get(): Role
    {
        return $this->role;
    }
}
