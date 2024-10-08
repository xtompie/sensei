<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Type\EnumId;

/**
 * @extends EnumId<MediaType>
 */
final class MediaType extends EnumId
{
    protected static array $valid = [
        'image',
    ];

    public static function image(): static
    {
        return new static(__FUNCTION__);
    }
}
