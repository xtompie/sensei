<?php

declare(strict_types=1);

namespace App\Media\Application\Service\CreateImageByUpload;

use App\Media\Application\Event\ImageCreated;
use App\Media\Application\Model\Image;
use App\Media\Application\Model\ImageMimeType;
use App\Media\Application\Model\ImageSpace;
use App\Media\Application\Model\MediaType;
use App\Media\Application\Service\GenerateId\GenerateId;
use App\Shared\Kernel\Dir;
use App\Shared\Messenger\Messenger;
use Laminas\Diactoros\UploadedFile;
use Xtompie\Result\Error;

class CreateImageByUpload
{
    public function __construct(
        private GenerateId $generateId,
        private Messenger $messenger,
    ) {
    }

    public function __invoke(UploadedFile $upload, ?string $name = null, ?ImageSpace $space = null): Error|Image
    {
        $uploadedImage = new UploadedImage(upload: $upload);

        $error = $uploadedImage->error();
        if ($error) {
            return $error;
        }

        $mimeTypeString = $uploadedImage->mimeType();
        if ($mimeTypeString === null) {
            return Error::of('Unrecognized mime type', 'unrecognized_mime_type');
        }

        $mimeType = ImageMimeType::tryFrom($mimeTypeString);
        if ($mimeType === null) {
            return Error::of('Invalid mime type', 'invalid_mime_type');
        }

        $id = $this->generateId->__invoke(
            type: MediaType::IMAGE,
            space: $space ?? ImageSpace::DEFAULT,
            name: $name ?? $uploadedImage->name() ?? MediaType::IMAGE->value,
            extension: $mimeType->extension()->value,
        );
        $image = new Image(id: $id);
        Dir::ensureForFile($image->path());
        $uploadedImage->moveTo($image->path());
        $this->messenger->__invoke(new ImageCreated(id: $image->id()));

        return $image;
    }
}
