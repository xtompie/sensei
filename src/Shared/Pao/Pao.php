<?php

declare(strict_types=1);

namespace App\Shared\Pao;

class Pao
{
    public static function hid(string ...$parts): string
    {
        return sha1(serialize($parts));
    }

    public function __construct(
        protected Fetcher $fetcher,
        protected Presister $presister,
    ) {
    }

    /**
     * @param array<string,mixed> $pql
     */
    public function count(array $pql, ?string $count = null): int
    {
        return $this->fetcher->count($pql, $count);
    }

    /**
     * @param array<string,mixed> $pql
     * @param array<callable> $hooks
     * @return array<string,mixed>|null
     */
    public function find(array $pql, array $hooks = []): ?array
    {
        return $this->fetcher->find($pql, $hooks);
    }

    /**
     * @param array<string,mixed> $pql
     * @param array<callable> $hooks
     * @return array<array<string, mixed>>
     */
    public function findAll(array $pql, array $hooks = []): array
    {
        return $this->fetcher->findAll($pql, $hooks);
    }

    /**
     * @param array<string,mixed> $future
     * @param callable|null $presentProvider
     * @param array<callable> $hooks
     */
    public function save(array $future, ?callable $presentProvider, array $hooks = []): void
    {
        $this->presister->save($future, $presentProvider, $hooks);
    }

    public function remove(callable $presentProvider): void
    {
        $this->presister->remove($presentProvider);
    }
}
