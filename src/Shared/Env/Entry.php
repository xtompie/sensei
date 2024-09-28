<?php

declare(strict_types=1);

namespace App\Shared\Env;

class Entry
{
    public function __construct(
        private string $key,
        private string $description,
        private bool $optional = false,
        private ?string $default = null,
    ) {
    }

    public function key(): string
    {
        return $this->key;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function optional(): bool
    {
        return $this->optional;
    }

    public function default(): ?string
    {
        return $this->default;
    }
}
