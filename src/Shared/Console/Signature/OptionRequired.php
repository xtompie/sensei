<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use App\Shared\Console\Option as ConsoleOption;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class OptionRequired
{
    public function __construct(
        public string $name,
        public ?string $description = null,
    ) {
    }

    public function toOption(): ConsoleOption
    {
        return new ConsoleOption(
            name: $this->name,
            description: $this->description,
            valueNone: false,
            valueRequired: true,
            valueOptional: false,
        );
    }
}
