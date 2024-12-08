<?php

declare(strict_types=1);

namespace App\Shared\Pao;

interface HookSync extends Hook
{
    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function syncCheck(array $present, array $future): ?SaveResult;

    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function syncFutureProjection(array $present, array $future): array;

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @param array<string,mixed> $where
     * @return array<string,mixed>
     */
    public function syncUpdateWhere(array $present, array $future, array $where): array;

    /**
     * @param array<string,mixed> $where
     * @return array<string,mixed>
     */
    public function syncAffectedRows(int $affectedRows): ?SaveResult;
}
