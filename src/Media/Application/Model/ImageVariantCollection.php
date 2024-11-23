<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Type\TypedCollection;

/**
 * @extends TypedCollection<ImageVariant>
 */
final class ImageVariantCollection extends TypedCollection
{
    public function filterByPreset(ImagePreset $preset): static
    {
        return $this->filter(fn (ImageVariant $variant) => $variant->preset() === $preset);
    }
}
