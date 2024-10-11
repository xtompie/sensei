<?php

declare(strict_types=1);

namespace App\Image\Application\Service\Replication;

use App\Image\Application\Model\Image;
use App\Image\Application\Model\ImageBlueprint;
use App\Image\Application\Repository\ImageRepository;
use App\Media\Application\Service\CreateImageByPathOrUrl\CreateImageByPathOrUrl;
use Xtompie\Result\Error;

class EnsureImage
{
    public function __construct(
        protected ImageRepository $imageRepository,
        protected CreateImageByPathOrUrl $createImageByPathOrUrl,
    ) {
    }

    public function __invoke(ImageBlueprint $blueprint): ?Image
    {
        $image = $this->imageRepository->findBySource($blueprint->source());
        if ($image instanceof Image) {
            return $image;
        }

        $mediaImage = $this->createImageByPathOrUrl->__invoke(pathOrUrl:$blueprint->source(), name: $blueprint->name());
        if ($mediaImage instanceof Error) {
            return null;
        }

        $blueprint = $blueprint->withMedia($mediaImage->id());

        $id = $this->imageRepository->create($blueprint);

        $image = $this->imageRepository->findById($id);

        return $image;
    }
}
