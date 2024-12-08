<?php

declare(strict_types=1);

namespace App\Shared\Pao;

interface HookLoadProjection extends Hook
{
    /**
     * @param array<string,mixed> $projection
     * @return array<string,mixed>
     */
    public function loadProjection(array $projection): array;
}
