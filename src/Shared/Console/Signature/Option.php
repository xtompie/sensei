<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use App\Shared\Console\Option as ConsoleOption;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class Option
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        protected bool $valueNone = false, // Do not accept input for the option (e.g. --yell). This is the default behavior of options.
        protected bool $valueRequired = false, // A value must be passed when the option is used (e.g. --iterations=5 or -i5).
        protected bool $valueOptional = false, // The option may or may not have a value (e.g. --yell or --yell=loud).
    ) {
    }

    public function toOption(): ConsoleOption
    {
        return new ConsoleOption(
            name: $this->name,
            description: $this->description,
            valueNone: $this->valueNone,
            valueRequired: $this->valueRequired,
            valueOptional: $this->valueOptional,
        );
    }
}
