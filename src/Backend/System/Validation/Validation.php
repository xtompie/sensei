<?php

declare(strict_types=1);

namespace App\Backend\System\Validation;

use App\Backend\System\Resource\Repository\ResourceRepository;
use App\Shared\Validation\Validation as BaseValidation;
use Xtompie\Validation\ValidationValidator;

class Validation extends BaseValidation
{
    public function __construct(
        protected ValidationValidator $validator,
        protected mixed $subject = null,
    ) {
    }

    /**
     * @param array<string,mixed>|null $entity
     */
    public function unique(ResourceRepository $repository, string $field, ?array $entity, ?string $msg = null): static
    {
        return $this->callback(
            fn (mixed $value) => 0 === $repository->count(
                array_filter([
                    $field => $value,
                    'id !=' => $entity['id'] ?? null,
                ])
            ),
            $msg ?? 'Value must be a unique'
        );
    }
}
