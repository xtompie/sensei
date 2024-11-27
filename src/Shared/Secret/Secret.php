<?php

declare(strict_types=1);

namespace App\Shared\Secret;

use App\Shared\Env\Env;
use App\Shared\Tenant\TenantContext;

class Secret
{
    public function __construct(
        private TenantContext $tenant,
        private Env $env,
    ) {
    }

    public function __invoke(): string
    {
        return hash_hmac('sha256', $this->tenant->id(), $this->env->APP_SECRET());
    }
}
