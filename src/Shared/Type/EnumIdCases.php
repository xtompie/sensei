<?php

declare(strict_types=1);

namespace App\Shared\Type;

/**
 * @template T of EnumIdCases
 * @phpstan-consistent-constructor
 * @extends EnumId<T>
 */
abstract class EnumIdCases extends EnumId
{
    /**
     * @var class-string<TypedCollection<T>>
     */
    protected static string $collection;

    /**
     * @return TypedCollection<T>
     */
    public static function cases(): object
    {
        $objects = array_map(fn ($value) => new static($value), static::$valid);
        return new static::$collection($objects);
    }
}
