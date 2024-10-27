<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Console\Command;
use App\Shared\Console\Signature\Name;
use App\Shared\Job\Stamp\SyncStamp;

#[Name('test')]
class TestCommand implements Command
{
    public function __invoke(Dispatcher $dispatcher): void
    {
        $job = new Test('Hello world!');
        $e = $dispatcher($job, [new SyncStamp()]);
        dd($e);
    }
}
