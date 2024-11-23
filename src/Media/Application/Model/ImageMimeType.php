<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

enum ImageMimeType: string
{
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';

    public function extension(): ImageExtension
    {
        return match ($this) {
            self::JPEG => ImageExtension::JPG,
            self::PNG => ImageExtension::PNG,
        };
    }
}
