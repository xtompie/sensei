<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Console\Command;
use App\Shared\Console\Output;

class CheckCommand
{
    public static function command(): Command
    {
        return new Command(name: 'app:env:check');
    }

    public function __construct(
        private Output $output,
        private Validator $validator,
    ) {
    }

    public function __invoke(): int
    {
        return $this->output->result($this->validator->__invoke());
    }
}
