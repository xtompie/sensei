<?php

declare(strict_types=1);

namespace App\Shared\Pao;

class HookLoadRowCallback implements HookLoadRow
{
    /**
     * @param callable(array<string,mixed>):array<string,mixed> $callback
     */
    public function __construct(
        private mixed $callback
    ) {
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public function loadRow(array $row): array
    {
        return ($this->callback)($row);
    }
}
