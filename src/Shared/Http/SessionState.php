<?php

declare(strict_types=1);

namespace App\Shared\Http;

class SessionState implements Session
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];
    protected bool $dirty = false;

    public function set(string $property, mixed $value): void
    {
        $this->data[$property] = $value;
        $this->dirty = true;
    }

    public function get(string $property): mixed
    {
        return $this->data[$property] ?? null;
    }

    public function has(string $property): bool
    {
        return isset($this->data[$property]);
    }

    public function remove(string $property): void
    {
        unset($this->data[$property]);
        $this->dirty = true;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    public function isDirty(): bool
    {
        return $this->dirty;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function load(array $data): void
    {
        $this->data = $data;
        $this->dirty = false;
    }
}
