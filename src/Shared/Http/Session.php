<?php

declare(strict_types=1);

namespace App\Shared\Http;

interface Session
{
    public function set(string $property, mixed $value): void;

    public function get(string $property): mixed;

    public function has(string $property): bool;

    public function remove(string $property): void;

    /**
     * @return array<string, mixed>
     */
    public function all(): array;
}
