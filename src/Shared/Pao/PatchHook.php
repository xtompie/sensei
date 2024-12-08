<?php

declare(strict_types=1);

namespace App\Shared\Pao;

class PatchHook implements HookSaveProjection
{
    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function saveProjection(?array $present, array $future): array
    {
        if ($present) {
            $future = array_merge($present, $future);
        }

        return $future;
    }
}
