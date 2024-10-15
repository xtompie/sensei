<?php

declare(strict_types=1);

namespace App\Backend\Resource\Admin;

use App\Backend\System\Resource\AbstractPilot;
use App\Backend\System\Validation\Validation;

class Pilot extends AbstractPilot
{
    /**
     * @param array<string, mixed> $entity
     */
    public function titleDetail(array $entity): string
    {
        /** @var string $email */
        $email = $entity['email'];
        return $email;
    }

    /**
     * @return array<string>
     */
    public function values(string $action): array
    {
        return [
            'email',
            'role',
        ];
    }

    /**
     * @param null|array<string, mixed> $entity
     */
    public function validation(Validation $validation, string $action, ?array $entity): Validation
    {
        return $validation
            ->key('email')->email()->required()
            ->group()
            ->key('email')->unique(repository: $this->repository(), field: 'email', entity: $entity)
        ;
    }

    /**
     * @return array<string, string>
     */
    public function roles(): array
    {
        return [
            'superadmin' => 'Superadmin',
            'admin' => 'Admin',
        ];
    }
}
