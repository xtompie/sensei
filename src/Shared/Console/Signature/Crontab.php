<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Crontab
{
    public function __construct(
        private string $expression,
    ) {
    }

    public function expression(): string
    {
        return $this->expression;
    }
}
