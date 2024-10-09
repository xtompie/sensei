<?php

declare(strict_types=1);

namespace App\Media\UI\Controller;

use App\Media\Application\Model\ImageVariant;
use App\Media\Application\Model\MediaType;
use App\Media\Application\Service\CreateImageVariant\CreateImageVariant;
use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;
use Xtompie\Result\Error;

#[Path(path: '/media/image/{path}', requirements: ['path' => '.+'])]
class ImageVariantController implements Controller
{
    public function __invoke(string $path, CreateImageVariant $createImageVariant): Response
    {
        $id = MediaType::image()->value() . '/' . $path;
        $variant = ImageVariant::tryFrom($id);
        if ($variant == null) {
            return Response::notFound();
        }

        $image = $variant->image();
        if (!$image->file()->isFile()) {
            return Response::notFound();
        }

        $result = $createImageVariant->__invoke(id: $variant->id());
        if ($result instanceof Error) {
            return Response::internalServerError();
        }

        return Response::file(path: $variant->path(), attachment: false);
    }
}
