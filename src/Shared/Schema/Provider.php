<?php

declare(strict_types=1);

namespace App\Shared\Schema;

use App\Shared\Kernel\Source;
use Doctrine\DBAL\Schema\Schema as DoctrineSchema;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Doctrine\Migrations\Provider\SchemaProvider;
use Generator;

class Provider implements SchemaProvider
{
    public function __construct(
        private Source $source,
    ) {
    }

    public function createSchema(): DoctrineSchema
    {
        $doctrineSchema = new DoctrineSchema();
        $this->tables($this->schema(), $doctrineSchema);
        return $doctrineSchema;
    }

    /**
     * @return Generator<Table>
     */
    private function schema(): Generator
    {
        foreach ($this->source->instances(instanceof: Schema::class, suffix: 'Schema') as $schema) {
            yield from $schema->tables();
        }
    }

    /**
     * @param Generator<Table> $tables
     */
    protected function tables(Generator $tables, DoctrineSchema $doctrineSchema): void
    {
        foreach ($tables as $table) {
            $this->table($table, $doctrineSchema);
        }
    }

    protected function table(Table $table, DoctrineSchema $doctrineSchema): void
    {
        $doctrineTable = $doctrineSchema->createTable($table->name());
        $this->columns($table->columns(), $doctrineTable);
        $this->indexes($table, $doctrineTable);
        if ($table->primary()) {
            $doctrineTable->setPrimaryKey($table->primary());
        }
    }

    /**
     * @param Column[] $columns
     */
    protected function columns(array $columns, DoctrineTable $doctrineTable): void
    {
        foreach ($columns as $column) {
            $this->column($column, $doctrineTable);
        }
    }

    protected function column(Column $column, DoctrineTable $doctrineTable): void
    {
        $doctrineTable->addColumn(
            name: $column->name(),
            typeName: $column->type()->__toString(),
            options: $this->options($column),
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function options(Column $column): array
    {
        $options = [];
        $options['notnull'] = !$column->nullable();
        if ($column->autoincrement()) {
            $options['autoincrement'] = true;
        }
        if ($column->length()) {
            $options['length'] = $column->length();
        }
        if ($column->default() !== null) {
            $options['default'] = $column->default();
        }
        if ($column->unsigned()) {
            $options['unsigned'] = true;
        }
        if ($column->type() instanceof StringType && $column->length() === null) {
            $options['length'] = 255;
        }
        return $options;
    }

    protected function indexes(Table $table, DoctrineTable $doctrineTable): void
    {
        foreach ($table->indexes() as $index) {
            $options = [];
            if ($index->lengths()) {
                $options['lengths'] = $index->lengths();
            }
            $name = $index->name() ?? 'idx_' . implode('_', $index->columns());
            if ($index->unique()) {
                $doctrineTable->addUniqueIndex($index->columns(), $name, $options);
            } else {
                $doctrineTable->addIndex($index->columns(), $name, [], $options);
            }
        }
    }
}
