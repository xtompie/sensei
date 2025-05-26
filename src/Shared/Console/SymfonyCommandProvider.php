<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Generator;
use Symfony\Component\Console\Command\Command;

interface SymfonyCommandProvider
{
    /**
     * @return Generator<Command>
     */
    public function __invoke(): Generator;
}
