<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use App\Shared\Gen\Gen;

class HookOptimisticLockState implements HookSync
{
    public function __construct(
        private string $field = 'state',
    ) {
    }

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     */
    public function syncCheck(array $present, array $future): ?SaveResult
    {
        if ($present[$this->field] !== $future[$this->field]) {
            return new SaveResultConflict();
        }

        return null;
    }

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function syncFutureProjection(array $present, array $future): array
    {
        $future[$this->field] = Gen::uuid4();

        return $future;
    }

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @param array<string,mixed> $where
     * @return array<string,mixed>
     */
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
