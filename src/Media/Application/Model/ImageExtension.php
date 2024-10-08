<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Type\EnumId;

/**
 * @extends EnumId<ImageExtension>
 */
final class ImageExtension extends EnumId
{
    protected static array $valid = [
        'jpg',
        'jpeg',
        'png',
    ];

    public static function jpg(): static
    {
        return new static(__FUNCTION__);
    }

    public static function jpeg(): static
    {
        return new static(__FUNCTION__);
    }

    public static function png(): static
    {
        return new static(__FUNCTION__);
    }
}
