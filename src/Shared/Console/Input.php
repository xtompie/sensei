<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Symfony\Component\Console\Input\InputInterface;

class Input
{
    public function __construct(
        protected Context $context,
    ) {
    }

    protected function input(): InputInterface
    {
        return $this->context->input();
    }

    public function option(string $name): mixed
    {
        return $this->input()->getOption($name);
    }

    public function optionBool(string $name): bool
    {
        $value = $this->option($name);
        return $value === true ? true : false;
    }

    public function optionIntOptional(string $name): ?int
    {
        $value = $this->option($name);
        return is_numeric($value) ? (int) $value : null;
    }

    public function optionStringOptional(string $name): ?string
    {
        $value = $this->option($name);
        return is_scalar($value) ? (string) $value : null;
    }

    public function argument(string $name): ?string
    {
        return is_scalar($this->input()->getArgument($name)) ? (string) $this->input()->getArgument($name) : null;
    }

    public function argumentString(string $name): string
    {
        return (string) $this->argument($name);
    }
}
