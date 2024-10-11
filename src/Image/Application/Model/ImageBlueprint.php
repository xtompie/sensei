<?php

declare(strict_types=1);

namespace App\Image\Application\Model;

final class ImageBlueprint
{
    /**
     * @param array{
     *  source: string,
     *  id?: string,
     *  media?: string,
     *  name?: string
     * } $primitive
     */
    public static function fromPrimitive(array $primitive): static
    {
        return new static(
            source: $primitive['source'],
            id: $primitive['id'] ?? null,
            media: $primitive['media'] ?? null,
            name: $primitive['name'] ?? null,
        );
    }

    public function __construct(
        protected string $source,
        protected ?string $id = null,
        protected ?string $media = null,
        protected ?string $name = null,
    ) {
    }

    /**
     * @return array{
     *  source: string,
     *  id: ?string,
     *  media: ?string,
     *  name: ?string
     * }
     */
    public function toPrimitive(): array
    {
        return [
            'source' => $this->source(),
            'id' => $this->id(),
            'media' => $this->media(),
            'name' => $this->name(),
        ];
    }

    public function source(): string
    {
        return $this->source;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): static
    {
        $new = clone $this;
        $new->id = $id;
        return $new;
    }

    public function media(): ?string
    {
        return $this->media;
    }

    public function withMedia(string $media): static
    {
        $new = clone $this;
        $new->media = $media;
        return $new;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function withName(string $name): static
    {
        $new = clone $this;
        $new->name = $name;
        return $new;
    }
}
