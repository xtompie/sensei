<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Http\IdentityChanged;
use App\Shared\Messenger\Messenger;

class LoginById
{
    public function __construct(
        private LoggedIdState $loggedIdState,
        private Messenger $messenger,
    ) {
    }

    public function __invoke(string $id): void
    {
        $this->loggedIdState->set($id);
        $this->messenger->__invoke(new IdentityChanged());
    }
}
