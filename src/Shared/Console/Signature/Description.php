<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Description implements Signature
{
    public function __construct(
        private string $description,
    ) {
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
