<?php

declare(strict_types=1);

namespace App\Shared\Pao;

final class OptimisticLockException extends PaoException
{
    public static function versionMismatch(string $table, string $id, string $expected, string $actual): static
    {
        return new static(
            "The optimistic lock failed on table $table on record with id $id, "
            . " version $expected was expected, but is actually $actual"
        );
    }
}
