<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Name implements Signature
{
    public function __construct(
        private string $name,
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
