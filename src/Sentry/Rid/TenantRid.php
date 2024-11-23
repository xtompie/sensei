<?php

declare(strict_types=1);

namespace App\Sentry\Rid;

use App\Sentry\System\Rid;

class TenantRid implements Rid
{
    public function __construct(
        private string $tenant,
    ) {
    }

    public function tenant(): string
    {
        return $this->tenant;
    }
}
