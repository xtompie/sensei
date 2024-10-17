<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;

#[Name('app:env:check')]
#[Description('Check the environment configuration')]
class CheckCommand implements Command
{
    public function __invoke(Output $output, Validator $validator): int
    {
        return $output->result($validator->__invoke());
    }
}
