<?php

declare(strict_types=1);

namespace App\Sentry\Application\Model;

use App\Shared\Type\EnumIdCases;

/**
 * @extends EnumIdCases<Role>
 */
final class Role extends EnumIdCases
{
    protected static array $valid = [
        'superadmin',
        'admin',
    ];

    /** @var class-string<RoleCollection> */
    protected static string $collection = RoleCollection::class;

    public static function superadmin(): static
    {
        return new static(__FUNCTION__);
    }

    public static function admin(): static
    {
        return new static(__FUNCTION__);
    }
}
