<?php

declare(strict_types=1);

namespace App\Sentry\System;

use App\Shared\Type\EnumIdCases;

/**
 * @extends EnumIdCases<Role>
 */
final class Role extends EnumIdCases
{
    protected static array $valid = [
        'guest',
        'superadmin',
        'admin',
    ];

    protected static string $collection = RoleCollection::class;

    public static function guest(): static
    {
        return new static(__FUNCTION__);
    }

    public static function superadmin(): static
    {
        return new static(__FUNCTION__);
    }

    public static function admin(): static
    {
        return new static(__FUNCTION__);
    }
}
