<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

use App\Shared\Pao\CreatedAtHook;
use App\Shared\Pao\PatchHook;
use App\Shared\Pao\Repository as BasePaoRepository;
use App\Shared\Pao\UpdatedAtHook;
use App\Shared\Tenant\TenantContext;
use Exception;
use Xtompie\Result\Result;

abstract class PaoRepository implements ResourceRepository
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    /**
     * @param BasePaoRepository<array<string,mixed>,array<array<string,mixed>>> $read
     * @param BasePaoRepository<array<string,mixed>,array<array<string,mixed>>> $write
     */
    public function __construct(
        protected BasePaoRepository $read,
        protected BasePaoRepository $write,
        protected TenantContext $tenantContext,
    ) {
        $this->read = $read
            ->withPql(fn (...$args) => $this->pqlForRead(...$args))
            ->withLoadRowHooks([fn (...$args) => $this->loadForRead(...$args)])
        ;
        $this->write = $write
            ->withPql(fn (...$args) => $this->pqlForWrite(...$args))
            ->withLoadRowHooks([fn (...$args) => $this->loadForWrite(...$args)])
            ->withSaveHooks($this->saveHooks())
        ;
    }

    protected function table(): string
    {
        $s = preg_replace('/(?<!^)[A-Z]/', '_$0', static::resource());
        if (!is_string($s)) {
            throw new Exception();
        }
        return strtolower($s);
    }

    protected function tenant(): bool
    {
        return false;
    }

    /**
     * @return array<string,mixed>
     */
    protected function static(): array
    {
        return array_filter([
            'tenant' => $this->tenant() ? $this->tenantContext->id() : null,
        ]);
    }

    /**
     * @return array<callable>
     */
    protected function saveHooks(): array
    {
        return [new CreatedAtHook(), new UpdatedAtHook(), new PatchHook()];
    }

    /**
     * @param array<string,mixed> $value
     * @return array<string,mixed>
     */
    protected function future(string $id, array $value): array
    {
        return [
            ...$value,
            ':table' => $this->table(),
            'id' => $id,
            ...$this->static(),
        ];
    }

    /**
     * @param array<string,mixed>|null $where
     * @return array<string,mixed>
     */
    protected function pql(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        $query = [
            'select' => $this->table() . '.*',
            'from' => $this->table(),
            'where' => [...$where ?? [], ...$this->static()],
            'order' => $order ? $this->order($order) : null,
            'limit' => $limit,
            'offset' => $offset,
        ];

        return array_filter($query);
    }

    /**
     * @param array<string,mixed>|null $where
     * @return array<string,mixed>
     */
    protected function pqlForWrite(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->pql($where, $order, $limit, $offset);
    }

    /**
     * @param array<string,mixed>|null $where
     * @return array<string,mixed>
     */
    protected function pqlForRead(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->pql($where, $order, $limit, $offset);
    }

    /**
     * @param array<string,mixed>|null $where
     */
    public function count(?array $where): int
    {
        return $this->read->count($where);
    }

    /**
     * @param array<string,mixed>|null $where
     * @return array<array<string, mixed>>
     */
    public function findAll(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        $entities = $this->read->findAll($where, $order, $limit, $offset);
        return $entities;
    }

    /**
     * @return array<string,mixed>|null
     */
    public function findById(string $id): ?array
    {
        return $this->read->find(['id' => $id]);
    }

    /**
     * @param array<string,mixed> $value
     */
    public function insert(string $id, array $value): Result
    {
        $this->write->save($this->future(id: $id, value: $value));
        return Result::ofSuccess();
    }

    /**
     * @param array<string,mixed> $value
     */
    public function update(string $id, array $value): Result
    {
        $this->write->save($this->future(id: $id, value: $value));
        return Result::ofSuccess();
    }

    public function delete(string $id): Result
    {
        $this->write->remove(id: $id);
        return Result::ofSuccess();
    }

    /**
     * @param array<string,mixed> $tuple
     * @return array<string,mixed>
     */
    protected function load(array $tuple): array
    {
        return $tuple;
    }

    /**
     * @param array<string,mixed> $tuple
     * @return array<string,mixed>
     */
    protected function loadForWrite(array $tuple): array
    {
        return $this->load($tuple);
    }

    /**
     * @param array<string,mixed> $tuple
     * @return array<string,mixed>
     */
    protected function loadForRead(array $tuple): array
    {
        return $this->load($tuple);
    }

    protected function order(string $order): string
    {
        if (str_ends_with($order, ':asc')) {
            return substr($order, 0, -strlen(':asc')) . ' ASC';
        } elseif (str_ends_with($order, ':desc')) {
            return  substr($order, 0, -strlen(':desc')) . ' DESC';
        } else {
            return $order;
        }
    }
}
