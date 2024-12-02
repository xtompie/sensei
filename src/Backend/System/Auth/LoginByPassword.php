<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

class LoginByPassword
{
    public function __construct(
        private LoginById $loginById,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(string $email, string $password): bool
    {
        $user = $this->userRepository->getByEmail($email);
        if ($user === null) {
            return false;
        }

        if (!$user->passwordMatches($password)) {
            return false;
        }

        $this->loginById->__invoke($user->id());

        return true;
    }
}
