<?php

declare(strict_types=1);

namespace App\Backend\Resource\Image;

use App\Backend\System\Resource\Pilot\ResourcePilot;
use App\Backend\System\Validation\Validation;
use App\Media\Application\Model\Image;

class Pilot extends ResourcePilot
{
    /**
     * @return array<string>
     */
    public function values(string $action): array
    {
        return [
            'media',
        ];
    }

    /**
     * @param null|array<string, mixed> $entity
     */
    public function validation(Validation $validation, string $action, ?array $entity): Validation
    {
        return $validation
            ->key('media')->callback(fn($v) => Image::tryFrom($v) instanceof Image)
        ;
    }
}
