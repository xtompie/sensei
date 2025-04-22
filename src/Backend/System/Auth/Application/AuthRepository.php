<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

use App\Shared\Gen\Gen;
use App\Shared\Tenant\TenantState;
use App\Shared\Type\Time;
use App\Shared\Validation\Validation;
use Xtompie\Dao\Repository;

class AuthRepository
{
    /**
     * @param Repository<Auth,array<array<string,mixed>>> $repository
     */
    public function __construct(
        private Repository $repository,
        private TenantState $tenantState,
    ) {
        $this->repository = $repository
            ->withTable('backend_user')
            ->withItemClass(Auth::class)
            ->withCallableStatic(fn () => ['tenant' => $this->tenantState->get()])
        ;
    }

    public function findById(string $id): ?Auth
    {
        return $this->repository->find(where: ['id' => $id]);
    }

    public function findByEmail(string $email): ?Auth
    {
        return $this->repository->find(where: ['email' => $email]);
    }

    public function findByResetToken(string $token): ?Auth
    {
        return $this->repository->find(where: ['reset_token' => $token]);
    }

    public function findByEmailAndPassword(string $email, string $password): ?Auth
    {
        $auth = $this->findByEmail($email);
        if ($auth === null) {
            return null;
        }

        if (!$auth->passwordEquals($password)) {
            return null;
        }

        return $auth;
    }

    public function reset(string $id): bool|string
    {
        $auth = $this->findById($id);
        if ($auth === null) {
            return false;
        }

        if ($auth->resetAt() && Time::now()->lt($auth->resetAt()->addSeconds(3600))) {
            return false;
        }

        $token = Gen::token();
        $this->repository->update(
            set: ['reset_token' => $token, 'reset_at' => Time::now()->toDateTimeString()],
            where: ['id' => $auth->id()],
        );

        return $token;
    }

    public function updatePasswordByResetToken(string $token, string $password): bool
    {
        if (Validation::of($token)->required()->fail()) {
            return false;
        }

        $auth = $this->findByResetToken($token);
        if (!$auth) {
            return false;
        }

        $this->repository->update(
            set: [
                'reset_token' => null,
                'reset_at' => null,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ],
            where: [
                'id' => $auth->id(),
            ],
        );

        return true;
    }
}
