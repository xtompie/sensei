<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Console\Command;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;

#[Name('app:optimize')]
#[Description('Optimize the application')]
class OptimizeCommand implements Command
{
    public function __invoke(Optimize $optimize): void
    {
        $optimize->__invoke();
    }
}
