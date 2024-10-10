<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Type\EnumId;

/**
 * @extends EnumId<ImageSpace>
 */
final class ImageSpace extends EnumId
{
    protected static array $valid = [
        'default',
    ];

    public static function default(): static
    {
        return new static(__FUNCTION__);
    }
}
