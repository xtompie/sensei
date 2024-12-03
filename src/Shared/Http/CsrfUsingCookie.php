<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Secret\Secret;
use Ramsey\Uuid\Uuid;

class CsrfUsingCookie extends Csrf
{
    public function __construct(
        private Request $request,
        private Secret $secret,
        private string $cookie = 'app.http.csrf',
    ) {
    }

    public function get(): string
    {
        $csrfId = $this->getCookieValue();
        if (!$csrfId) {
            $csrfId = $this->generate();
            $this->setCookie($csrfId);
        }

        return hash_hmac('sha256', $csrfId, $this->secret->__invoke());
    }

    private function generate(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function revoke(): void
    {
        $this->setCookie(null);
    }

    public function verify(): bool
    {
        return $this->hasValidCsrfToken()
            && $this->hasValidOrigin()
            && $this->hasValidReferer()
        ;
    }

    private function hasValidCsrfToken(): bool
    {
        $csrfId = $this->getCookieValue();
        $body = $this->request->body();
        $csrfToken = $body['_csrf'] ?? null;

        if (!is_string($csrfId) || !is_string($csrfToken)) {
            return false;
        }

        $validToken = hash_hmac('sha256', $csrfId, $this->secret->__invoke());
        return hash_equals($validToken, $csrfToken);
    }

    private function hasValidOrigin(): bool
    {
        $origin = $this->request->getHeader('Origin')[0] ?? null;
        if (empty($origin)) {
            return true;
        }

        $allowedDomain = $this->request->getUri()->getHost();
        return parse_url($origin, PHP_URL_HOST) === $allowedDomain;
    }

    private function hasValidReferer(): bool
    {
        $referer = $this->request->getHeader('Referer')[0] ?? null;
        if (empty($referer)) {
            return true;
        }

        $allowedDomain = $this->request->getUri()->getHost();
        return parse_url($referer, PHP_URL_HOST) === $allowedDomain;
    }

    private function getCookieValue(): ?string
    {
        $cookies = $this->request->getCookieParams();
        return $cookies[$this->cookie] ?? null;
    }

    private function setCookie(?string $value): void
    {
        $expires = $value === null ? time() - 3600 : 0;
        $value = $value ?? '';

        setcookie(
            name: $this->cookie,
            value: $value,
            expires_or_options: [
                'expires' => $expires,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict',
            ]
        );
    }
}
