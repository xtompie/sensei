<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

class ProfileConsoleExitCode
{
    public function __construct(
        private Data $data,
    ) {
    }

    public function __invoke(int $exitCode): void
    {
        $this->data->add(type: 'shared.console.exitcode', data: $exitCode);
    }
}
