<?php

declare(strict_types=1);

namespace App\Media\UI\Controller;

use App\Media\Application\Model\ImageVariant;
use App\Media\Application\Service\CreateImageByUpload\CreateImageByUpload;
use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\POST;
use Xtompie\Result\Error;

#[Path(path: '/media/image/upload'), POST]
class ImageUploadController implements Controller
{
    public function __invoke(
        Request $request,
        CreateImageByUpload $createImageByUpload,
    ): Response|Error {
        $upload = $request->getUploadedFiles()['upload'] ?? null;
        if (!$upload) {
            return Error::of('No param upload');
        }

        $result = $createImageByUpload->__invoke($upload);
        if ($result instanceof Error) {
            return $result;
        }

        return Response::json([
            'id' => $result->id(),
            'name' => $result->name(),
            'variants' => $result->variants()->toArray(fn (ImageVariant $variant) => [
                'name' => $variant->preset()->value(),
                'url' => $variant->url(),
            ]),
        ]);
    }
}
