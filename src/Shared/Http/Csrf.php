<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Ramsey\Uuid\Uuid;

class Csrf
{
    public function __construct(
        private SharedSession $sharedSession,
        private Request $request,
        private bool $enabled = true,
    ) {
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function enable(bool $enable): void
    {
        $this->enabled = $enable;
    }

    public function get(): string
    {
        $csrf = $this->sharedSession->get('csrf');
        if (!$csrf || !is_string($csrf)) {
            $csrf = Uuid::uuid4()->toString();
            $this->sharedSession->set('csrf', $csrf);
        }

        return $csrf;
    }

    public function verify(): bool
    {
        $body = $this->request->body();
        if (!is_array($body)) {
            return false;
        }
        if (!isset($body['_csrf'])) {
            return false;
        }
        if (!is_string($body['_csrf'])) {
            return false;
        }

        return hash_equals($this->get(), $body['_csrf']);
    }
}
