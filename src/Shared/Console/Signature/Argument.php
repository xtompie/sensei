<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use App\Shared\Console\Argument as ConsoleArgument;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Argument
{
    public function __construct(
        private string $name,
        private ?string $description = null,
        private bool $optional = false,
    ) {
    }

    public function toArgument(): ConsoleArgument
    {
        return new ConsoleArgument(
            name: $this->name,
            description: $this->description,
            optional: $this->optional,
        );
    }
}
