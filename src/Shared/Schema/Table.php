<?php

declare(strict_types=1);

namespace App\Shared\Schema;

class Table
{
    /**
     * @param Column[] $columns
     * @param array<Index> $indexes
     * @param array<string> $primary
     */
    public function __construct(
        protected string $name,
        protected array $columns,
        protected array $primary = [],
        protected array $indexes = [],
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Column[]
     */
    public function columns(): array
    {
        return $this->columns;
    }

    /**
     * @return Index[]
     */
    public function indexes(): array
    {
        return [
            ...$this->indexesFromColumns(),
            ...$this->indexes,
        ];
    }

    /**
     * @return Index[]
     */
    protected function indexesFromColumns(): array
    {
        $indexes = [];
        foreach ($this->columns() as $column) {
            if ($column->index() || $column->unique()) {
                $indexes[] = new Index(
                    name: null,
                    columns: [$column->name()],
                    unique: $column->unique(),
                );
            }
        }
        return $indexes;
    }

    /**
     * @return ?array<string>
     */
    public function primary(): ?array
    {
        if ($this->primary) {
            return $this->primary;
        }
        foreach ($this->columns() as $column) {
            if ($column->primary()) {
                return [$column->name()];
            }
        }

        return null;
    }
}
