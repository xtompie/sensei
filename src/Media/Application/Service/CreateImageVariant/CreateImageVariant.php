<?php

declare(strict_types=1);

namespace App\Media\Application\Service\CreateImageVariant;

use App\Media\Application\Model\ImageVariant;
use App\Shared\Kernel\Dir;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Xtompie\Result\Error;

final class CreateImageVariant
{
    public function __invoke(string $id): ImageVariant|Error
    {
        $variant = ImageVariant::tryFrom($id);
        if (!$variant) {
            return Error::of('Invalid id', 'invalid', 'id');
        }

        $image = $variant->image();
        if (!$image->file()->isFile()) {
            return Error::of('Image not found', 'image_not_found');
        }

        Dir::ensureForFile($variant->path());

        (new ImageManager(
            driver: new Driver(),
        ))
            ->read(input: $image->path())
            ->scale(width: $variant->preset()->width(), height: $variant->preset()->height())
            ->save(path: $variant->path())
        ;

        return $variant;
    }
}
