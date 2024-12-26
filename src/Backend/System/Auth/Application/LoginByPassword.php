<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

class LoginByPassword
{
    public function __construct(
        private LoginById $loginById,
        private AuthRepository $authRepository,
    ) {
    }

    public function __invoke(string $email, string $password): bool
    {
        $auth = $this->authRepository->findByEmailAndPassword(email: $email, password: $password);
        if ($auth === null) {
            return false;
        }

        $this->loginById->__invoke($auth->id());
        return true;
    }
}
