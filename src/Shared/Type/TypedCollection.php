<?php

declare(strict_types=1);

namespace App\Shared\Type;

/**
 * @template T
 * @phpstan-consistent-constructor
 */
abstract class TypedCollection
{
    /**
     * @param array<T> $collection
     */
    public function __construct(
        protected array $collection
    ) {
    }

    /**
     * @return array<T>
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function any(): bool
    {
        return (bool) $this->collection;
    }

    /**
     * @param (callable(T,int):void|callable(T):void) $fn
     * @return static
     */
    public function each(callable $fn): static
    {
        foreach ($this->collection as $k => $v) {
            $fn($v, $k);
        }
        return $this;
    }

    /**
     * @param (callable(T,int):bool|callable(T):bool)|null $fn
     * @return static
     */
    public function filter(?callable $fn = null): static
    {
        if ($fn === null) {
            $fn = fn (mixed $v, int $k): bool => !empty($v);
        }
        return new static(array_values(array_filter($this->collection, $fn, ARRAY_FILTER_USE_BOTH))); // @phpstan-ignore-line
    }

    /**
     * @return T|null
     */
    public function first(): mixed
    {
        foreach ($this->collection as $i) {
            return $i;
        }
        return null;
    }

    public function none(): bool
    {
        return !(bool) $this->collection;
    }

    /**
     * @param callable(T,int):mixed|null $map
     * @return array<T>|array<mixed>
     */
    public function toArray(?callable $map = null): array
    {
        $result = $this->collection;
        if ($map !== null) {
            $result = array_map($map, $result, array_keys($result));
            $result = array_filter($result);
            $result = array_values($result);
        }
        return $result;
    }

    /**
     * @template Y of object
     * @param class-string<Y> $class
     * @param callable(T, int):mixed|null $map
     * @return Y
     */
    public function to(string $class, ?callable $map = null): object
    {
        $result = $this->collection;
        if ($map) {
            $result = array_map($map, $result, array_keys($result));
            $result = array_filter($result);
            $result = array_values($result);
        }
        return new $class($result);
    }
}
