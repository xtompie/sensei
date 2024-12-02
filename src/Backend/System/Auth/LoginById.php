<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Http\IdentityChanged;
use App\Shared\Messenger\Messenger;

class LoginById
{
    public function __construct(
        private LoggedUserIdState $loggedUserIdState,
        private Messenger $messenger,
    ) {
    }

    public function __invoke(string $id): void
    {
        $this->loggedUserIdState->set($id);
        $this->messenger->__invoke(new IdentityChanged());
    }
}
