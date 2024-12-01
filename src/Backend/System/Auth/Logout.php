<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

class Logout
{
    public function __construct(
        protected LoggedUserIdState $loggedUserIdState,
    ) {
    }

    public function __invoke(): void
    {
        $this->loggedUserIdState->clear();
    }
}
