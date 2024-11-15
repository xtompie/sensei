<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Schema\Provider as SchemaProvider;
use App\Shared\Kernel\AppDir;
use App\Shared\Env\Env;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\SchemaProvider as DoctrineSchemaProvider;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
use Generator;
use Symfony\Component\Console\Command\Command as SymfonyCommnd;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Xtompie\Container\Container;

final class ApplicationProvider
{
    public function __construct(
        private CommandDiscoverer $commandDiscoverer,
        private CommandDefinitionResolver $commandDefinitionResolver,
        private SchemaProvider $schemaProvider,
    ) {
    }

    public function __invoke(): Application
    {
        $application = new Application();
        $application->setAutoExit(false);
        foreach ($this->commands() as $command) {
            $application->add($this->symfony($command));
        }
        $this->doctrine($application);
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

    private function doctrine(Application $application): void
    {
        $container = Container::container();
        $appDir = $container->get(AppDir::class);
        $env = $container->get(Env::class);
        $connection = DriverManager::getConnection(
            params: [
                'dbname' => $env->APP_DB_NAME(),
                'driver' => 'pdo_mysql',
                'host' => $env->APP_DB_HOST(),
                'password' => $env->APP_DB_PASS(),
                'user' => $env->APP_DB_USER(),
            ],
        );
        $di = DependencyFactory::fromConnection(
            configurationLoader: new ConfigurationArray([
                'table_storage' => [
                    'table_name' => 'doctrine_migration_versions',
                    'version_column_name' => 'version',
                    'version_column_length' => 191,
                    'executed_at_column_name' => 'executed_at',
                    'execution_time_column_name' => 'execution_time',
                ],
                'migrations_paths' => [
                    'Migrations' => $appDir->__invoke() . '/tools/migrations',
                ],
                'all_or_nothing' => true,
                'transactional' => true,
                'check_database_platform' => true,
                'organize_migrations' => 'none',
                'connection' => null,
                'em' => null,
            ]),
            connectionLoader: new ExistingConnection(
                connection: $connection,
            ),
        );
        $di->setDefinition(
            id: DoctrineSchemaProvider::class,
            service: fn () => $this->schemaProvider,
        );
        $application->addCommands([
            (new DoctrineCommand\DiffCommand($di))->setName('app:db:diff'),
            // (new DoctrineCommand\DumpSchemaCommand($di))->setName('app:db:dump-schema'),
            // (new DoctrineCommand\ExecuteCommand($di))->setName('app:db:migrations:execute'),
            // (new DoctrineCommand\GenerateCommand($di))->setName('app:db:migrations:generate'),
            // (new DoctrineCommand\LatestCommand($di))->setName('app:db:migrations:latest'),
            // (new DoctrineCommand\ListCommand($di))->setName('app:db:migrations:list'),
            (new DoctrineCommand\MigrateCommand($di))->setName('app:db:migrate'),
            // (new DoctrineCommand\RollupCommand($di))->setName('app:db:migrations:rollup'),
            // (new DoctrineCommand\StatusCommand($di))->setName('app:db:migrations:status'),
            // (new DoctrineCommand\SyncMetadataCommand($di))->setName('app:db:migrations:sync-metadata'),
            // (new DoctrineCommand\VersionCommand($di))->setName('app:db:migrations:version'),
        ]);
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
