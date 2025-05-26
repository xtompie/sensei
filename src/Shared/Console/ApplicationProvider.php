<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Generator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as SymfonyCommnd;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Xtompie\Container\Container;

final class ApplicationProvider
{
    public function __construct(
        private CommandDiscoverer $commandDiscoverer,
        private CommandDefinitionResolver $commandDefinitionResolver,
        private SymfonyCommandProviderDiscoverer $symfonyCommandProviderDiscoverer
    ) {
    }

    public function __invoke(): Application
    {
        $application = new Application();
        $application->setAutoExit(false);
        foreach ($this->commands() as $command) {
            $application->add($this->symfony($command));
        }
        foreach ($this->symfonyCommandProviderDiscoverer->instances() as $provider) {
            foreach ($provider->__invoke() as $command) {
                $application->add($command);
            }
        }
        return $application;
    }

    private function symfony(CommandDefinition $definition): SymfonyCommnd
    {
        /** @var Bridge $bridge */
        $bridge = Container::container()->resolve(
            abstract: Bridge::class,
            values: [
                'command' => $definition,
            ]
        );

        $bridge->setName($definition->name());
        if ($definition->description()) {
            $bridge->setDescription($definition->description());
        }

        foreach ($definition->arguments() as $argument) {
            $bridge->addArgument(
                name: $argument->name(),
                mode: $argument->optional() ? InputArgument::OPTIONAL : InputArgument::REQUIRED,
                description: $argument->description() ?? '',
            );
        }

        foreach ($definition->options() as $option) {
            $bridge->addOption(
                name: $option->name(),
                mode:
                    ($option->valueNone() ? InputOption::VALUE_NONE : 0)
                    | ($option->valueRequired() ? InputOption::VALUE_REQUIRED : 0)
                    | ($option->valueOptional() ? InputOption::VALUE_OPTIONAL : 0),
                description: $option->description() ?? '',
            );
        }

        return $bridge;
    }

    /**
     * @return Generator<CommandDefinition>
     */
    private function commands(): Generator
    {
        $unique = [];
        foreach ($this->commandDiscoverer->classes() as $class) {
            if (isset($unique[$class])) {
                continue;
            }
            $unique[$class] = true;
            if (!class_exists($class)) {
                return throw new \InvalidArgumentException('Command must be a valid class-string.');
            }

            $command = $this->commandDefinitionResolver->__invoke($class);

            yield $command;
        }
    }
}
