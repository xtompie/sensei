<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Carbon\Carbon;

class UpdatedAtHook
{
    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function __invoke(?array $present, array $future): array
    {
        $future['updated_at'] = Carbon::now()->toDateTimeString();

        return $future;
    }
}
