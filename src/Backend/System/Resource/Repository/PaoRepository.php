<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

use App\Shared\Pao\CreatedAtHook;
use App\Shared\Pao\PatchHook;
use App\Shared\Pao\Repository as BasePaoRepository;
use App\Shared\Pao\UpdatedAtHook;
use Exception;
use Xtompie\Result\Result;

abstract class PaoRepository implements ResourceRepository
{
    public static function resource(): string
    {
        return strtolower(array_slice(explode('\\', static::class), -2, 1)[0]);
    }

    public function __construct(
        protected BasePaoRepository $repository,
    ) {
        $this->repository = $repository
            ->withPql(fn (...$args) => $this->pql(...$args))
            ->withLoadRowHooks([fn (...$args) => $this->load(...$args)])
            ->withSaveHooks([new CreatedAtHook(), new UpdatedAtHook(), new PatchHook()])
        ;
    }

    /**
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function future(string $id, array $value): array
    {
        return [
            ...$value,
            ':table' => static::resource(),
            'id' => $id,
        ];
    }

    /**
     * @param array<string, mixed>|null $where
     * @return array<string, mixed>
     */
    protected function pql(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        $query = [
            'select' => static::resource() . '.*',
            'from' => static::resource(),
        ];

        $query['where'] = (isset($query['where']) || isset($where)) ? array_merge($query['where'] ?? [], (array) $where) : null; /** @phpstan-ignore-line */
        $query['order'] = $order ? $this->order($order) : null;
        $query['limit'] = $limit;
        $query['offset'] = $offset;

        return $query;
    }

    /**
     * @param array<string, mixed>|null $where
     */
    public function count(?array $where): int
    {
        return $this->repository->count($where);
    }

    /**
     * @param array<string, mixed>|null $where
     * @return array<array<string, mixed>>
     */
    public function findAll(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        $entities = $this->repository->findAll($where, $order, $limit, $offset);
        if (!is_array($entities)) {
            throw new Exception();
        }
        return $entities;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(string $id): ?array
    {
        $entity = $this->repository->find(['id' => $id]);
        if (!is_array($entity) && !is_null($entity)) {
            throw new Exception();
        }
        return $entity;
    }

    /**
     * @param array<string, mixed> $value
     */
    public function save(string $id, array $value): Result
    {
        $this->repository->save($this->future(id: $id, value: $value));
        return Result::ofSuccess();
    }

    public function remove(string $id): Result
    {
        $this->repository->remove(id: $id);
        return Result::ofSuccess();
    }

    /**
     * @param array<string, mixed> $tuple
     * @return array<string, mixed>
     */
    protected function load(array $tuple): array
    {
        return $tuple;
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
