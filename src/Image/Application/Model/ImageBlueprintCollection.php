<?php

declare(strict_types=1);

namespace App\Image\Application\Model;

use App\Shared\Type\TypedCollection;

/**
 * @extends TypedCollection<ImageBlueprint>
 */
final class ImageBlueprintCollection extends TypedCollection
{
    /**
     * @param array<array{
     *  source: string,
     *  id?: string,
     *  media?: string,
     *  name?: string
     * }> $primitive
     */
    public static function fromPrimitive(array $primitive): static
    {
        return new static(array_map(
            fn (array $i) => ImageBlueprint::fromPrimitive($i),
            $primitive
        ));
    }

    public function unique(): static
    {
        $unique = [];
        foreach ($this->all() as $imageBlueprint) {
            $unique[$imageBlueprint->source()] = $imageBlueprint;
        }
        $unique = array_values($unique);
        return new static($unique);
    }
}
