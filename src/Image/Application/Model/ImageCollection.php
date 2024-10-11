<?php

declare(strict_types=1);

namespace App\Image\Application\Model;

use App\Shared\Type\TypedCollection;

/**
 * @extends TypedCollection<Image>
 */
class ImageCollection extends TypedCollection
{
    public function blueprints(): ImageBlueprintCollection
    {
        return $this->to(ImageBlueprintCollection::class, fn (Image $image) => $image->blueprint());
    }
}
