<?php

declare(strict_types=1);

namespace App\Shared\Type;

abstract class ValueId
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ValueId $value): bool
    {
        return $this->value === $value->value && static::class === get_class($value);
    }
}
