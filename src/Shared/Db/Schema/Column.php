<?php

declare(strict_types=1);

namespace App\Shared\Db\Schema;

class Column
{
    public function __construct(
        protected string $name,
        protected Type $type,
        protected bool $primary = false,
        protected bool $unique = false,
        protected bool $index = false,
        protected bool $nullable = false,
        protected bool $autoincrement = false,
        protected ?int $length = null,
        protected mixed $default = null,
        protected bool $unsigned = false,
        protected bool $zerofill = false,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function primary(): bool
    {
        return $this->primary;
    }

    public function unique(): bool
    {
        return $this->unique;
    }

    public function index(): bool
    {
        return $this->index;
    }

    public function nullable(): bool
    {
        return $this->nullable;
    }

    public function autoincrement(): bool
    {
        return $this->autoincrement;
    }

    public function length(): ?int
    {
        return $this->length;
    }

    public function default(): mixed
    {
        return $this->default;
    }

    public function unsigned(): bool
    {
        return $this->unsigned;
    }

    public function zerofill(): bool
    {
        return $this->zerofill;
    }
}
