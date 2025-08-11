<?php

declare(strict_types=1);

namespace App\Shared\Pao;

class HookOptimisticLockVersion implements HookSync
{
    public function __construct(
        private string $field = 'version',
    ) {
    }

    public function syncCheck(array $present, array $future): ?SaveResult
    {
        if ($present[$this->field] !== $future[$this->field]) {
            return new SaveResultConflict();
        }

        return null;
    }

    public function syncFutureProjection(array $present, array $future): array
    {
        $future[$this->field] = $present[$this->field] + 1;

        return $future;
    }

    public function syncUpdateWhere(array $present, array $future, array $where): array
    {
        $where[$this->field] = $present[$this->field];

        return $where;
    }

    public function syncAffectedRows(int $affectedRows): ?SaveResult
    {
        if ($affectedRows === 0) {
            return new SaveResultConflict();
        }

        return null;
    }
}
