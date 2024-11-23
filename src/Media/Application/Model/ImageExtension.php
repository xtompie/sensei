<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

enum ImageExtension: string
{
    case JPG = 'jpg';
    case JPEG = 'jpeg';
    case PNG = 'png';
}
