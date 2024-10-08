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
     * @var array<string>
     */
    protected static array $valid = [];

    public function __construct(string $value)
    {
        if (!in_array($value, static::$valid, true)) {
            throw new \InvalidArgumentException("Invalid value for EnumId: $value");
        }
        parent::__construct(value: $value);
    }

    public static function tryFrom(string $value): ?static
    {
        return in_array($value, static::$valid, true) ? new static($value) : null;
    }
}
