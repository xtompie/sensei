<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Name
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
