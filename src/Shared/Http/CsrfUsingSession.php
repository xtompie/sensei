<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Ramsey\Uuid\Uuid;

class CsrfUsingSession extends Csrf
{
    public function __construct(
        private SessionProperty $sessionProperty,
        private Request $request,
    ) {
        $this->sessionProperty = $sessionProperty->withProperty('shared.csrf');
    }

    public function get(): string
    {
        $csrf = $this->sessionProperty->getAsString();
        if (!$csrf) {
            $csrf = $this->generate();
            $this->sessionProperty->set($csrf);
        }

        return $csrf;
    }

    private function generate(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function revoke(): void
    {
        $this->sessionProperty->remove();
    }

    public function verify(): bool
    {
        $body = $this->request->body();
        if (!isset($body['_csrf'])) {
            return false;
        }
        if (!is_string($body['_csrf'])) {
            return false;
        }

        return hash_equals($this->get(), $body['_csrf']);
    }
}
