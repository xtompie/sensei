<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use ReflectionClass;
use Xtompie\Collection\Collection;

/**
 * @template TItem
 * @template TCollection
 */
class Repository
{
    /**
     * @param callable $pql
     * @param class-string<TCollection>|null $collectionClass
     * @param class-string<TItem>|null $itemClass
     * @param array<Hook> $hooks
     * @param null|array<string,array<string,mixed>> $cache
     */
    public function __construct(
        protected Pao $pao,
        protected mixed $pql = null,
        protected array $hooks = [],
        protected ?string $collectionClass = null,
        protected ?string $itemClass = null,
        protected mixed $itemFactory = null,
        protected ?array $cache = null,
    ) {
    }

    public function withPql(callable $pql): static
    {
        $new = clone $this;
        $new->pql = $pql;
        return $new;
    }

    /**
     * @param array<Hook> $hooks
     */
    public function withHooks(array $hooks): static
    {
        $new = clone $this;
        $new->hooks = $hooks;
        return $new;
    }

    /**
     * @param class-string<TCollection> $collectionClass
     */
    public function withCollectionClass(string $collectionClass): static
    {
        $new = clone $this;
        $new->collectionClass = $collectionClass;
        return $new;
    }

    /**
     * @param class-string<TItem> $itemClass
     */
    public function withItemClass(string $itemClass): static
    {
        $new = clone $this;
        $new->itemClass = $itemClass;
        return $new;
    }

    public function withItemFactory(callable $itemFactory): static
    {
        $new = clone $this;
        $new->itemFactory = $itemFactory;
        return $new;
    }

    public function withCache(bool $cache): static
    {
        $new = clone $this;
        $new->cache = $cache ? [] : null;
        return $new;
    }

    /**
     * @param array<string,mixed>|null $where
     */
    public function count(?array $where = null, ?string $count = null): int
    {
        $pql = ($this->pql)($where);
        if ($count === null && isset($pql['group'])) {
            $count = 'DISTINCT ' . (is_array($pql['group']) ? 'CONCAT(' . implode(', ', $pql['group']) . ')' : $pql['group']);
            unset($pql['prefix']);
            unset($pql['group']);
        }
        return $this->pao->count($pql, $count);
    }

    /**
     * @param array<string,mixed>|null $where
     * @return TItem[]|TCollection
     */
    public function findAll(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): mixed
    {
        return Collection::of(
            $this->pao->findAll(
                pql: ($this->pql)($where, $order, $limit, $offset),
                hooks: $this->hooks
            ),
        )
            ->into(
                $this->collectionClass,
                fn (array $projection) => $this->item($projection),
            )
        ;
    }

    /**
     * @param array<string,mixed>|null $where
     * @return TItem|null
     */
    public function find(?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): mixed
    {
        $projection = $this->pao->find(($this->pql)($where, $order, $limit, $offset), $this->hooks);
        if (!$projection) {
            return null;
        }
        return $this->item($projection);
    }

    /**
     * @return TItem|null
     */
    public function findById(string $id): mixed
    {
        if ($this->cache !== null && isset($this->cache[$id])) {
            return $this->item($this->cache[$id]);
        }
        $projection = $this->pao->find(($this->pql)(['id' => $id]), $this->hooks);
        if (!$projection) {
            return null;
        }
        if ($this->cache !== null) {
            $this->cache[$id] = $projection;
        }
        return $this->item($projection);
    }

    /**
     * @param array<string,mixed> $projection
     */
    public function save(array $projection): void
    {
        /** @var array{id:string} $projection */
        $this->pao->save(
            future: $projection,
            presentProvider: $this->presentProvider($projection['id']),
            hooks: $this->hooks
        );
    }

    public function remove(string $id): void
    {
        $this->pao->remove($this->presentProvider($id));
    }

    protected function presentProvider(string $id): callable
    {
        return function () use ($id) {
            return $this->findById($id);
        };
    }

    /**
     * @param array<string,mixed> $projection
     * @return TItem
     */
    protected function item(array $projection): mixed
    {
        if ($this->itemFactory && is_callable($this->itemFactory)) {
            return ($this->itemFactory)($projection);
        } elseif ($this->itemClass && class_exists($this->itemClass)) {
            /** @var class-string $class */
            $class = $this->itemClass;
            $item = (new ReflectionClass($class))->newInstance($projection);
            /** @var TItem $item */
            return $item;
        } else {
            return $projection; // @phpstan-ignore-line
        }
    }
}
