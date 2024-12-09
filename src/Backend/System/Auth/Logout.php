<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Http\IdentityChanged;
use App\Shared\Messenger\Messenger;

class Logout
{
    public function __construct(
        protected LoggedState $loggedState,
        protected Messenger $messenger,
    ) {
    }

    public function __invoke(): void
    {
        $this->loggedState->remove();
        $this->messenger->__invoke(new IdentityChanged());
    }
}
