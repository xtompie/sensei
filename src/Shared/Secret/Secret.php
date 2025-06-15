<?php

declare(strict_types=1);

namespace App\Shared\Secret;

use App\Shared\Env\Env;

class Secret
{
    public function __construct(
        private Env $env,
    ) {
    }

    public function __invoke(): string
    {
        return $this->env->APP_SECRET();
    }
}
