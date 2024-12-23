<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

use App\Shared\Http\IdentityChanged;
use App\Shared\Messenger\Messenger;

class LoginById
{
    public function __construct(
        private LoggedState $loggedState,
        private Messenger $messenger,
    ) {
    }

    public function __invoke(string $id): void
    {
        $this->loggedState->set($id);
        $this->messenger->__invoke(new IdentityChanged());
    }
}
