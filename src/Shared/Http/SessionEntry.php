<?php

declare(strict_types=1);

namespace App\Shared\Http;

final class SessionEntry
{
    public function __construct(
        private Session $session,
        private string $property,
    ) {
    }

    public function set(mixed $value): void
    {
        $this->session->set($this->property, $value);
    }

    public function get(): mixed
    {
        return $this->session->get($this->property);
    }

    public function getAsString(): ?string
    {
        $value = $this->get();
        return is_string($value) ? $value : null;
    }

    public function remove(): void
    {
        $this->session->remove($this->property);
    }

    public function add(mixed $item): void
    {
        $current = $this->get();
        if (!is_array($current)) {
            $current = [];
        }
        $current[] = $item;
        $this->set($current);
    }

    public function pull(): mixed
    {
        $value = $this->get();
        $this->remove();
        return $value;
    }
}
