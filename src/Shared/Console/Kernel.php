<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Container\Container;
use App\Shared\Kernel\Debug;
use App\Shared\Profiler\ConsoleReporter;
use App\Shared\Profiler\ProfileConsoleExitCode;
use App\Shared\Profiler\ProfileStart;
use App\Shared\Profiler\ProfileStop;

class Kernel
{
    public function __construct(
        protected Application $application,
        protected Debug $debug,
    ) {
    }

    public function __invoke(): int
    {
        if ($this->debug->__invoke()) {
            Container::container()->get(ProfileStart::class)->__invoke();
        }

        $exitCode = $this->application->run();

        if ($this->debug->__invoke()) {
            Container::container()->get(ProfileConsoleExitCode::class)->__invoke($exitCode);
            Container::container()->get(ProfileStop::class)->__invoke();
            Container::container()->get(ConsoleReporter::class)->__invoke();
        }

        return $exitCode;
    }
}
