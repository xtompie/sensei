<?php

declare(strict_types=1);

namespace App\Shared\Console;

class Argument
{
    public function __construct(
        protected string $name,
        protected ?string $description,
        protected bool $optional = false,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function optional(): bool
    {
        return $this->optional;
    }
}
