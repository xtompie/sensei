<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Messenger\Subscriber;

class IdentityChangedSubscriber implements Subscriber
{
    public function __construct(
        private Session $session,
        private Csrf $csrf,
    ) {
    }

    public function __invoke(IdentityChanged $identityChanged): void
    {
        $this->csrf->revoke();
        $this->session->regenerateId();
    }
}
