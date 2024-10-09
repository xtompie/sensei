<?php

declare(strict_types=1);

namespace App\Media\Application\Service\CreateImage;

use App\Media\Application\Model\Image;
use App\Media\Application\Model\ImageMimeType;
use App\Media\Application\Model\MediaType;
use App\Media\Application\Service\GenerateId\GenerateId;
use App\Shared\Gen\Gen;
use App\Shared\Kernel\Dir;
use Xtompie\Result\Error;

final class CreateImage
{
    public function __construct(
        private GenerateId $generateId,
    ) {
    }

    public function __invoke(string $pathOrUrl, ?string $name = null, string $space = 'default'): Image|Error
    {
        if (strlen($space) === 0) {
            return Error::of('Space cannot be empty', 'invalid_space', 'space');
        }

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

        $id = $this->generateId->__invoke(
            type: MediaType::image(),
            space: $space,
            name: $this->resolveName($mimeType, $pathOrUrl, $name)
        );

        $image = new Image(id: $id);

        Dir::ensureForFile($image->path());

        if (!@copy($tmp, $image->path())) {
            $error = error_get_last();
            return Error::of($error['message'] ?? null, 'create');
        }
        unlink($tmp);

        return $image;
    }

    protected function resolveName(ImageMimeType $mimeType, string $fallback, ?string $name): string
    {
        [$fallback] = explode('?', $fallback, 2);
        $name = $name ?: $fallback;
        if ($this->extension($name) !== $mimeType->extension()->value()) {
            $name = $this->nameWithoutExtension($name) . '.' . $mimeType->extension()->value();
        }
        return $name;
    }

    protected function extension(string $name): string
    {
        return pathinfo($name, PATHINFO_EXTENSION);
    }

    protected function nameWithoutExtension(string $name): string
    {
        return pathinfo($name, PATHINFO_FILENAME);
    }
}
