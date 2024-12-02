<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

class GetLoggedUser
{
    public function __construct(
        protected LoggedUserIdState $loggedUserIdState,
        protected UserRepository $userRepository,
    ) {
    }

    public function __invoke(): ?User
    {
        $id = $this->loggedUserIdState->get();
        if ($id === null) {
            return null;
        }

        return $this->userRepository->findById($id);
    }
}

