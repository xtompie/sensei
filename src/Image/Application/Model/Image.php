<?php

declare(strict_types=1);

namespace App\Image\Application\Model;

use App\Media\Application\Model\Image as MediaImage;

class Image
{
    /**
     * @param array{
     *  id: string,
     *  media: string,
     *  type: string,
     *  source: string
     * } $data
     */
    public function __construct(
        protected array $data,
    ) {
    }

    public function id(): string
    {
        return $this->data['id'];
    }

    public function media(): string
    {
        return $this->data['media'];
    }

    public function type(): string
    {
        return $this->data['type'];
    }

    public function source(): string
    {
        return $this->data['source'];
    }

    public function mediaImage(): MediaImage
    {
        return new MediaImage(id: $this->media());
    }

    public function blueprint(): ImageBlueprint
    {
        return new ImageBlueprint(
            source: $this->source(),
            id: $this->id(),
            media: $this->media(),
            name: $this->mediaImage()->name(),
        );
    }
}
