<?php

declare(strict_types=1);

namespace App\Example\UI\Command;

use App\Shared\Console\Aop\Lock;
use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Console\Signature\Name;

#[Name('example')]
class ExampleCommand implements Command
{
    #[Lock(error: false)]
    public function __invoke(Output $output): void
    {
        sleep(10);
        $output->write('Hello, World!');

    }
}
