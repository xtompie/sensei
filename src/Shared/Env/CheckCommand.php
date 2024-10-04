<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;

class CheckCommand implements Command
{
    public function __construct(
        private Output $output,
        private Validator $validator,
    ) {
    }

    #[Name('app:env:check')]
    #[Description('Check environment variables')]
    public function __invoke(): int
    {
        return $this->output->result($this->validator->__invoke());
    }
}
