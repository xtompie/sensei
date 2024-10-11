<?php

declare(strict_types=1);

namespace App\Media\Application\Service\RenameImage;

use App\Media\Application\Model\Image;
use App\Media\Application\Model\ImageVariant;

class RenameImage
{
    public function __invoke(Image $source, string $name): Image
    {
        $destination = $source->withName($name);
        if ($source->equals($destination)) {
            return $source;
        }

        rename($source->path(), $destination->path());

        $source->variants()->each(
            function (ImageVariant $variant): void {
                $variant->file()->isFile() ? unlink($variant->path()) : null;
            }
        );

        return $destination;
    }
}
