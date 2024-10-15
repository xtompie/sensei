<?php

declare(strict_types=1);

namespace App\Shared\Http;

class UrlParameterContext
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        protected array $context = [],
    ) {
    }

    public function set(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->context[$key]);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->context);
    }

    /**
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return $this->context;
    }

    public function any(): bool
    {
        return (bool) $this->context;
    }
}
