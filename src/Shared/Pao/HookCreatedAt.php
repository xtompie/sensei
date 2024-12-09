<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Carbon\Carbon;

class HookCreatedAt implements HookSaveProjection
{
    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function saveProjection(?array $present, array $future): array
    {
        if ($present === null) {
            $future['created_at'] = Carbon::now()->toDateTimeString();
        }

        return $future;
    }
}
