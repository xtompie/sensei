<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Ramsey\Uuid\Uuid;

class CsrfUsingSession implements Csrf
{
    private SessionEntry $sessionEntry;

    public function __construct(
        private Request $request,
        SessionEntryFactory $sessionEntryFactory,
    ) {
        $this->sessionEntry = $sessionEntryFactory->__invoke('shared.csrf');
    }

    public function get(): string
    {
        $csrf = $this->sessionEntry->getAsString();
        if (!$csrf) {
            $csrf = $this->generate();
            $this->sessionEntry->set($csrf);
        }

        return $csrf;
    }

    private function generate(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function revoke(): void
    {
        $this->sessionEntry->remove();
    }

    public function verify(): bool
    {
        return hash_equals($this->get(), $this->request->csrf());
    }
}
