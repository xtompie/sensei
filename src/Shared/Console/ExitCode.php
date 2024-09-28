<?php

declare(strict_types=1);

namespace App\Shared\Console;

class ExitCode
{
    public function __construct(
        protected Context $context,
    ) {
    }

    public function set(int $code): void
    {
        $this->context->updateExitCode($code);
    }
}
