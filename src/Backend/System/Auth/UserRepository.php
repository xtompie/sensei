<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Tenant\TenantContext;
use Xtompie\Dao\Repository;

class UserRepository
{
    /**
     * @param Repository<User,array<User>> $repository
     */
    public function __construct(
        private Repository $repository,
        private TenantContext $tenantContext,
    ) {
        $this->repository = $repository
            ->withTable('backend_user')
            ->withCallableStatic(fn () => ['tenant' => $this->tenantContext->id()])
            ->withItemClass(User::class)
        ;
    }

    public function findById(string $id): ?User
    {
        return $this->repository->find(['id' => $id]);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->repository->find(['email' => $email]);
    }

    /**
     * @param array<string,mixed>|null $where
     * @return array<User>
     */
    public function findAll(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findAll($where, $order, $limit, $offset);
    }
}
