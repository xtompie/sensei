<?php

declare(strict_types=1);

namespace App\Shared\Console;

class Command
{
    /**
     * @param string $name
     * @param class-string<object> $command
     * @param ?string $description
     * @param array<Argument> $arguments
     * @param array<Option> $options
     */
    public function __construct(
        protected string $name,
        protected ?string $command = null,
        protected ?string $description = null,
        protected array $arguments = [],
        protected array $options = [],
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return ?class-string<object>
     */
    public function command(): ?string
    {
        return $this->command;
    }

    /**
     * @param class-string $command
     * @return void
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @return array<Option>
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return array<Argument>
     */
    public function arguments(): array
    {
        return $this->arguments;
    }
}
