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
        protected ApplicationProvider $applicationProvider,
        protected Debug $debug,
    ) {
    }

    public function __invoke(): int
    {
        $debug = $this->debug->__invoke();

        ini_set('log_errors', '1');
        error_reporting($debug ? E_ALL : E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);

        if ($debug) {
            Container::container()->get(ProfileStart::class)->__invoke();
        }

        $exitCode = $this->applicationProvider->__invoke()->run();

        if ($debug) {
            Container::container()->get(ProfileConsoleExitCode::class)->__invoke($exitCode);
            Container::container()->get(ProfileStop::class)->__invoke();
            Container::container()->get(ConsoleReporter::class)->__invoke();
        }

        return $exitCode;
    }
}
