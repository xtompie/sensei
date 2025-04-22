<?php

declare(strict_types=1);

namespace App\Shared\Secret;

use App\Shared\Env\Env;
use App\Shared\Tenant\TenantState;

class Secret
{
    public function __construct(
        private TenantState $tenantState,
        private Env $env,
    ) {
    }

    public function __invoke(): string
    {
        return hash_hmac('sha256', (string) $this->tenantState->get(), $this->env->APP_SECRET());
    }
}
