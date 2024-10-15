<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Type\EnumId;

/**
 * @extends EnumId<UrlReference>
 */
final class UrlReference extends EnumId
{
    protected static array $valid = [
        'absoluteUrl',
        'absolutePath',
        'relativePath',
        'newtworkPath',
    ];

    public static function absoluteUrl(): static
    {
        return new static(__FUNCTION__);
    }

    public static function absolutePath(): static
    {
        return new static(__FUNCTION__);
    }

    public static function relativePath(): static
    {
        return new static(__FUNCTION__);
    }

    public static function newtworkPath(): static
    {
        return new static(__FUNCTION__);
    }
}
