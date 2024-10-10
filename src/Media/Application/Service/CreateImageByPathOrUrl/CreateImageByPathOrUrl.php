<?php

declare(strict_types=1);

namespace App\Media\Application\Service\CreateImageByPathOrUrl;

use App\Media\Application\Model\Image;
use App\Media\Application\Model\ImageMimeType;
use App\Media\Application\Model\ImageSpace;
use App\Media\Application\Model\MediaType;
use App\Media\Application\Service\GenerateId\GenerateId;
use App\Shared\Gen\Gen;
use App\Shared\Kernel\Dir;
use Xtompie\Result\Error;

final class CreateImageByPathOrUrl
{
    public function __construct(
        private GenerateId $generateId,
    ) {
    }

    public function __invoke(string $pathOrUrl, ?string $name = null, ?ImageSpace $space = null): Image|Error
    {
        if ($name !== null && strlen($name) === 0) {
            return Error::of('Name cannot be empty', 'invalid_name', 'name');
        }

        $tmp = sys_get_temp_dir() . '/' . Gen::uuid4();

        if (!@copy($pathOrUrl, $tmp)) {
            return Error::of(error_get_last()['message'] ?? null, 'create');
        }

        $mimeTypeString = mime_content_type($tmp);
        if ($mimeTypeString === false) {
            unlink($tmp);
            return Error::of('Unrecognized mime type', 'unrecognized_mime_type');
        }

        $mimeType = ImageMimeType::tryFrom($mimeTypeString);
        if ($mimeType === null) {
            unlink($tmp);
            return Error::of('Invalid mime type', 'invalid_mime_type');
        }

        $id = $this->generateId(space: $space, mimeType: $mimeType, pathOrUrl: $pathOrUrl, name: $name);

        $image = new Image(id: $id);

        Dir::ensureForFile($image->path());

        if (!@copy($tmp, $image->path())) {
            $error = error_get_last();
            return Error::of($error['message'] ?? null, 'create');
        }
        unlink($tmp);

        return $image;
    }

    private function generateId(?ImageSpace $space, ImageMimeType $mimeType, string $pathOrUrl, ?string $name): string
    {
        if ($name === null) {
            [$fallback] = explode('?', $pathOrUrl, 2);
            $name = pathinfo($fallback, PATHINFO_FILENAME);
        }

        return $this->generateId->__invoke(
            type: MediaType::image(),
            space: $space ?? ImageSpace::default(),
            name: $name,
            extension: $mimeType->extension()->value(),
        );
    }
}
