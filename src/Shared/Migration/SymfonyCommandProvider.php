<?php

declare(strict_types=1);

namespace App\Shared\Migration;

use App\Shared\Console\SymfonyCommandProvider as ConsoleSymfonyCommandProvider;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Generator;

class SymfonyCommandProvider implements ConsoleSymfonyCommandProvider
{
    public function __construct(
        private DependencyFactoryProvider $dependencyFactoryProvider,
    ) {
    }

    public function __invoke(): Generator
    {
        $dependencyFactory = $this->dependencyFactoryProvider->__invoke();
        yield (new DiffCommand($dependencyFactory))->setName('app:db:diff');
        yield (new MigrateCommand($dependencyFactory))->setName('app:db:migrate');
    }
}
