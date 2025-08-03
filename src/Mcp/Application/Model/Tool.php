<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

use Closure;

final class Tool
{
    /**
     * @param Parameter[] $parameters
     * @param array<string,mixed>|null $annotations
     */
    public function __construct(
        private string $name,
        private string $description,
        private Closure $handler,
        private array $parameters,
        private Type $return,
        private ?string $title = null,
        private ?Type $outputSchema = null,
        private ?array $annotations = null
    ) {
    }

    /**
     * @param array<string,mixed> $arguments
     */
    public function call(array $arguments): mixed
    {
        return ($this->handler)(...$arguments);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return Parameter[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function return(): Type
    {
        return $this->return;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function outputSchema(): ?Type
    {
        return $this->outputSchema;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function annotations(): ?array
    {
        return $this->annotations;
    }
}
