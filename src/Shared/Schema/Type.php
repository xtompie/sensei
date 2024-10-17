<?php

declare(strict_types=1);

namespace App\Shared\Schema;

class Type
{
    public function __construct(
        protected string $type,
    ) {
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
