<?php

declare(strict_types=1);

namespace App\Shared\Db\Schema;

class Index
{
    /**
     * @param array<string> $columns
     * @param ?string $name
     * @param bool $unique
     * @param ?array<int> $lengths
     */
    public function __construct(
        protected array $columns,
        protected ?string $name = null,
        protected bool $unique = false,
        protected ?array $lengths = null,
    ) {
    }

    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * @return array<string>
     */
    public function columns(): array
    {
        return $this->columns;
    }

    public function unique(): bool
    {
        return $this->unique;
    }

    /**
     * @return ?array<int>
     */
    public function lengths(): ?array
    {
        return $this->lengths;
    }
}
