<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Http\IdentityChanged;
use App\Shared\Messenger\Messenger;

class Logout
{
    public function __construct(
        protected LoggedIdState $loggedIdState,
        protected Messenger $messenger,
    ) {
    }

    public function __invoke(): void
    {
        $this->loggedIdState->remove();
        $this->messenger->__invoke(new IdentityChanged());
    }
}
