<?php

declare(strict_types=1);

namespace App\Shared\Type;

abstract class ValueId
{
    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(ValueId $id): bool
    {
        return $this->id === $id->id && static::class === get_class($id);
    }
}
