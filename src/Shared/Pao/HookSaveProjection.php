<?php

declare(strict_types=1);

namespace App\Shared\Pao;

interface HookSaveProjection extends Hook
{
    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function saveProjection(?array $present, array $future): array;
}
