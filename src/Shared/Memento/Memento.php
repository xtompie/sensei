<?php

declare(strict_types=1);

namespace App\Shared\Memento;

class Memento
{
    /**
     * @param array<string,array<string, mixed>> $cache
     */
    public function __construct(
        private array $cache = [],
    ) {
    }

    public function has(string $space, string $key): bool
    {
        return isset($this->cache[$space], $this->cache[$space][$key]);
    }

    public function get(string $space, string $key): mixed
    {
        return $this->cache[$space][$key] ?? null;
    }

    public function set(string $space, string $key, mixed $value): void
    {
        $this->cache[$space][$key] = $value;
    }

    public function remove(string $space, string $key): void
    {
        unset($this->cache[$space][$key]);
    }

    public function clear(string $space): void
    {
        unset($this->cache[$space]);
    }

    public function resolve(string $space, string $key, callable $data): mixed
    {
        if (!$this->has($space, $key)) {
            $data = $data();
            $this->set($space, $key, $data);
            return $data;
        }

        return $this->get($space, $key);
    }
}
