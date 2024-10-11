<?php

declare(strict_types=1);

namespace App\Media\Application\Service\RenameImage;

use App\Media\Application\Model\Image;

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
            fn (Image $variant) =>
            $variant->file()->isFile() ? unlink($variant->path()) : null
        );

        return $destination;
    }
}
