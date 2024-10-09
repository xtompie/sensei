<?php

declare(strict_types=1);

namespace App\Shared\Http;

class ControllerMeta
{
    /**
     * @param string $path
     * @param string $controller
     * @param array<string, string> $requirements
     * @param array<string> $methods
     * @param array<string, mixed> $defaults
     */
    public function __construct(
        protected string $path,
        protected ?string $controller = null,
        protected array $requirements = [],
        protected array $methods = [],
        protected array $defaults = [],
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function controller(): ?string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return array<string, string>
     */
    public function requirements(): array
    {
        return $this->requirements;
    }

    /**
     * @return array<string>
     */
    public function methods(): array
    {
        return $this->methods;
    }

    /**
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return $this->defaults;
    }
}
