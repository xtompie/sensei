<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

use Xtompie\Result\Result;

interface ResourceRepository
{
    public static function resource(): string;

    /**
     * @param array<string,mixed>|null $where
     */
    public function count(?array $where = null): int;

    /**
     * @param array<string,mixed>|null $where
     * @return array<int,array<string, mixed>>
     */
    public function findAll(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @return array<string,mixed>|null $where
     */
    public function findById(string $id): ?array;

    /**
     * @param array<string,mixed> $value
     */
    public function insert(?string $id, array $value): Result;

    /**
     * @param array<string,mixed> $value
     */
    public function update(string $id, array $value): Result;

    public function delete(string $id): Result;
}
