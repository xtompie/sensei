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
     * @param array<callable> $loadHooks
     * @param array<callable> $loadRowHooks
     * @param array<callable> $saveHooks
     */
    public function __construct(
        protected Pao $pao,
        protected mixed $pql = null,
        protected array $hooks = [],
        protected mixed $readPql = null,
        protected array $readHooks = [],
        protected ?string $collectionClass = null,
        protected ?string $itemClass = null,
        protected mixed $itemFactory = null,
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

    public function withReadPql(callable $pql): static
    {
        $new = clone $this;
        $new->readPql = $pql;
        return $new;
    }

    /**
     * @param array<Hook> $hooks
     */
    public function withReadHooks(array $hooks): static
    {
        $new = clone $this;
        $new->readHooks = $hooks;
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
            $this->pao->findAll(($this->pql)($where, $order, $limit, $offset), $this->readHooks ?: $this->hooks),
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
        $projection = $this->pao->find(($this->pql)($where, $order, $limit, $offset), $this->readHooks ?: $this->hooks);
        if (!$projection) {
            return null;
        }
        return $this->item($projection);
    }

    /**
     * @param array<string,mixed> $projection
     */
    public function save(array $projection): void
    {
        $this->pao->save($projection, $this->presentProvider($projection['id']), $this->hooks); // @phpstan-ignore-line
    }

    public function remove(string $id): void
    {
        $this->pao->remove($this->presentProvider($id));
    }

    protected function presentProvider(string $id): callable
    {
        return function () use ($id) {
            return $this->pao->find(($this->pql)(['id' => $id]));
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
            return $projection;
        }
    }
}
