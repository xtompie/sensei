<?php

declare(strict_types=1);

namespace App\Shared\Type;

/**
 * @template T of EnumId
 * @phpstan-consistent-constructor
 */
abstract class EnumId extends ValueId
{
    /**
     * @var class-string<TypedCollection<T>>
     */
    protected static string $collection;

    /**
     * @var array<string>
     */
    protected static array $valid = [];

    public function __construct(string $id)
    {
        if (!in_array($id, static::$valid, true)) {
            throw new \InvalidArgumentException("Invalid value for EnumId: $id");
        }
        parent::__construct($id);
    }

    public static function tryFrom(string $value): ?static
    {
        return in_array($value, static::$valid, true) ? new static($value) : null;
    }

    /**
     * @return TypedCollection<T>
     */
    public static function cases(): object
    {
        $objects = array_map(fn ($value) => new static($value), static::$valid);
        return new static::$collection($objects);
    }
}
