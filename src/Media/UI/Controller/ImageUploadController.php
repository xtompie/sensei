<?php

declare(strict_types=1);

namespace App\Media\UI\Controller;

use App\Media\Application\Model\ImageVariant;
use App\Media\Application\Model\MediaType;
use App\Media\Application\Service\CreateImageVariantById\CreateImageVariantById;
use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\POST;
use Xtompie\Result\Error;

#[Path(path: '/media/image/upload'), POST]
class ImageVariantController implements Controller
{
    public function __invoke(string $path, CreateImageVariantById $createImageVariantById): Response
    {
/*
        $response = $this->ctrl->__invoke(sentry: 'backend.media.create');
        if ($response) {
            return $response;
        }

        if (!$this->requester->isPost()) {
            return $this->form();
        }

        if (!$this->requester->request()->files->has('image')) {
            return $this->responder->result(Result::ofErrorMsg('No param `image`'));
        }

        $upload = $this->requester->request()->files->get('image');
        $result = $this->uploadImageService->__invoke($upload);

        if ($result->fail()) {
            return $this->responder->result(Result::ofErrors($result->errors()));
        }

        $image = $result->image();

        return $this->responder->result(Result::ofValue([
            'identifier' => $image->identifier(),
            'name' => basename($upload->getClientOriginalName(), '.' . $upload->getClientOriginalExtension()),
            'variants' => $image->variants()->into(null, fn (ImageVariant $imageVariant) => [
                'name' => $imageVariant->variantName(),
                'url' => $imageVariant->url(),
            ]),
        ]));


*/
    }
}
