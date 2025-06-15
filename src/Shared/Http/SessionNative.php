<?php

declare(strict_types=1);

namespace App\Shared\Http;

final class SessionNative implements Session
{
    public function __construct(
        private bool $inited = false,
    ) {
    }

    private function init(): void
    {
        if ($this->inited) {
            return;
        }

        $this->inited = true;
        session_start();
    }

    public function active(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function set(string $property, mixed $value): void
    {
        $this->init();
        $_SESSION[$property] = $value;
    }

    public function get(string $property): mixed
    {
        $this->init();
        return $_SESSION[$property] ?? null;
    }

    public function has(string $property): bool
    {
        $this->init();
        return isset($_SESSION[$property]);
    }

    public function remove(string $property): void
    {
        $this->init();
        unset($_SESSION[$property]);
    }

    /**
     * @return array<mixed>
     */
    public function all(): array
    {
        $this->init();
        return $_SESSION;
    }

    public function regenerateId(): void
    {
        session_regenerate_id(delete_old_session: true);
    }
}
