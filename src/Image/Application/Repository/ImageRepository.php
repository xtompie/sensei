<?php

declare(strict_types=1);

namespace App\Image\Application\Repository;

use App\Image\Application\Model\Image;
use App\Image\Application\Model\ImageBlueprint;
use App\Image\Application\Model\ImageCollection;
use App\Shared\Gen\Gen;
use Xtompie\Dao\Repository;

class ImageRepository
{
    /**
     * @param Repository<Image, ImageCollection> $repository
     */
    public function __construct(
        protected Repository $repository,
    ) {
        $this->repository = $repository
            ->withTable('image')
            ->withItemClass(Image::class)
            ->withCollectionClass(ImageCollection::class)
        ;
    }

    public function findById(string $id): ?Image
    {
        return $this->repository->find(['id' => $id]);
    }

    public function findBySource(string $source): ?Image
    {
        return $this->repository->find(['source' => $source]);
    }

    public function create(ImageBlueprint $blueprint): string
    {
        $id = $blueprint->id() ?? Gen::id();

        $this->repository->insert(
            values: [
                'id' => $id,
                'media' => $blueprint->media(),
                'source' => $blueprint->source(),
            ],
        );

        return $id;
    }
}
