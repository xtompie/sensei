<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

use App\Sentry\System\RoleContext;

class GetLoggedAuth
{
    protected ?Auth $auth = null;
    protected bool $init = false;

    public function __construct(
        protected LoggedState $loggedState,
        protected AuthRepository $authRepository,
        protected RoleContext $roleContext,
    ) {
    }

    public function __invoke(): ?Auth
    {
        if ($this->init === false) {
            $this->init = true;

            $id = $this->loggedState->get();
            if ($id === null) {
                return null;
            }

            $this->auth = $this->authRepository->findById($id);

            if ($this->auth) {
                $this->roleContext->set($this->auth->role());
            }
        }

        return $this->auth;
    }
}
