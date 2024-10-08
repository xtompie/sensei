<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Type\EnumId;

/**
 * @extends EnumId<ImageMimeType>
 */
final class ImageMimeType extends EnumId
{
    protected static array $valid = [
        'image/jpeg',
        'image/png',
    ];

    public static function imageJpeg(): static
    {
        return new static(__FUNCTION__);
    }

    public static function imagePng(): static
    {
        return new static(__FUNCTION__);
    }

    public function extension(): ImageExtension
    {
        return match ($this->value) {
            'image/jpeg' => ImageExtension::jpg(),
            'image/png' => ImageExtension::png(),
            default => throw new \InvalidArgumentException('Invalid image mime type'),
        };
    }
}
