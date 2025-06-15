<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Crypter\Crypter;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class SessionCookieDriver implements Advice
{
    public function __construct(
        private string $cookieName = 'session',
    ) {
    }

    public function __invoke(Invocation $invocation, Crypter $crypter, SessionState $session): mixed
    {
        $this->load(crypter: $crypter, session: $session);
        $result = $invocation();
        $this->save(crypter: $crypter, session: $session);
        return $result;
    }

    public function load(Crypter $crypter, SessionState $session): void
    {
        $data = [];
        if (!isset($_COOKIE[$this->cookieName])) {
            return;
        }
        $raw = $_COOKIE[$this->cookieName];
        if (!is_string($raw)) {
            return;
        }
        $raw = $crypter->decrypt($raw);
        if (!is_string($raw)) {
            return;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return;
        }
        foreach (array_keys($data) as $key) {
            if (!is_string($key)) {
                return;
            }
        }
        /** @var array<string, mixed> $data */
        $session->load($data);
    }

    public function save(Crypter $crypter, SessionState $session): void
    {
        if (!$session->isDirty()) {
            return;
        }

        $raw = (string) json_encode($session->all());
        $raw = $crypter->encrypt($raw);

        if (!is_string($raw)) {
            return;
        }

        setcookie(
            name: $this->cookieName,
            value: $raw,
            expires_or_options: [
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );
    }
}
