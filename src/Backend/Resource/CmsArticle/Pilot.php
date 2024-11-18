<?php

declare(strict_types=1);

namespace App\Backend\Resource\CmsArticle;

use App\Backend\System\Resource\Pilot\ResourcePilot;
use App\Backend\System\Validation\Validation;

class Pilot extends ResourcePilot
{
    /**
     * @return array<string>
     */
    public function values(string $action): array
    {
        return [
            'title',
            'body',
        ];
    }

    /**
     * @param null|array<string, mixed> $entity
     */
    public function validation(Validation $validation, string $action, ?array $entity): Validation
    {
        return $validation
            ->key('title')->required()
            ->group()
            ->key('url')->unique(repository: $this->repository(), field: 'url', entity: $entity)
        ;
    }

    public function titlePlural(): string
    {
        return 'Articles';
    }
}
