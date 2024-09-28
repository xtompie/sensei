<?php

declare(strict_types=1);

namespace App\Shared\Console;

class Option
{
    public function __construct(
        protected string $name,
        protected ?string $description,
        protected bool $valueNone = false, // Do not accept input for the option (e.g. --yell). This is the default behavior of options.
        protected bool $valueRequired = false, // A value must be passed when the option is used (e.g. --iterations=5 or -i5).
        protected bool $valueOptional = false, // The option may or may not have a value (e.g. --yell or --yell=loud).
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

    public function valueNone(): bool
    {
        return $this->valueNone;
    }

    public function valueRequired(): bool
    {
        return $this->valueRequired;
    }

    public function valueOptional(): bool
    {
        return $this->valueOptional;
    }
}
