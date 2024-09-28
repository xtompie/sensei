<?php

declare(strict_types=1);

namespace App\Shared\Http;

class Session
{
    public function __construct(
        protected ?string $space = null
    ) {
        $this->activate();
    }

    protected function activate(): void
    {
        if ($this->active()) {
            return;
        }
        session_start();
    }

    public function active(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function withSpace(string $space): static
    {
        $clone = clone $this;
        $clone->space = $space;
        return $clone;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $this->activate();
        if ($this->space) {
            return $_SESSION[$this->space] ?? [];
        }
        return $_SESSION;
    }

    public function set(string $key, mixed $value): void
    {
        $this->activate();
        if ($this->space) {
            $_SESSION[$this->space][$key] = $value;
        } else {
            $_SESSION[$key] = $value;
        }
    }

    public function pull(string $key): mixed
    {
        $data = $this->get($key);
        $this->remove($key);
        return $data;
    }

    public function get(string $key): mixed
    {
        $this->activate();
        if ($this->space) {
            return $_SESSION[$this->space][$key] ?? null;
        }
        return $_SESSION[$key] ?? null;
    }

    public function remove(string $key): void
    {
        $this->activate();
        if ($this->space) {
            unset($_SESSION[$this->space][$key]);
            if (empty($_SESSION[$this->space])) {
                unset($_SESSION[$this->space]);
            }
        } else {
            unset($_SESSION[$key]);
        }
    }
}
