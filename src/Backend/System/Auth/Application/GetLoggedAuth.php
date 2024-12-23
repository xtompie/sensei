<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

class GetLoggedAuth
{
    public function __construct(
        protected LoggedState $loggedState,
        protected AuthRepository $authRepository,
    ) {
    }

    public function __invoke(): ?Auth
    {
        $id = $this->loggedState->get();
        if ($id === null) {
            return null;
        }

        return $this->authRepository->findById($id);
    }
}
