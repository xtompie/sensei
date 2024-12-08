<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Tenant\TenantContext;
use Xtompie\Dao\Repository;

class AuthRepository
{
    /**
     * @param Repository<array<string,mixed>,array<array<string,mixed>>> $repository
     */
    public function __construct(
        private Repository $repository,
        private TenantContext $tenantContext,
    ) {
        $this->repository = $repository
            ->withTable('backend_user')
            ->withCallableStatic(fn () => ['tenant' => $this->tenantContext->id()])
        ;
    }

    public function findById(string $id): ?Auth
    {
        $tuple = $this->repository->find(['id' => $id]);
        if ($tuple === null) {
            return null;
        }
        return new Auth($tuple);
    }

    public function getByEmailAndPassword(string $email, string $password): ?Auth
    {
        $tuple = $this->repository->find(['email' => $email]);
        if ($tuple === null) {
            return null;
        }

        if ($tuple['password'] === null || $tuple['password'] === '') {
            return null;
        }

        if (!password_verify($password, $tuple['password'])) {
            return null;
        }

        return new Auth($tuple);
    }
}
