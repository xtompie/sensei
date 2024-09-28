<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

use App\Shared\Env\Env;

final class Debug
{
    public function __construct(
        private Env $env,
        private ?bool $debug = null,
    ) {
    }

    public function __invoke(): bool
    {
        return $this->get();
    }

    public function get(): bool
    {
        if ($this->debug === null) {
            $this->debug = $this->env->APP_DEBUG() === '1';
        }

        return $this->debug;
    }
}
