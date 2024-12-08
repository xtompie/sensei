<?php

declare(strict_types=1);

namespace App\Shared\Pao;

interface HookLoadRow extends Hook
{
    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public function loadRow(array $row): array;
}
