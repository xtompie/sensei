<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Registry\Console;
use App\Shared\Console\Signature\Argument as SignatureArgument;
use App\Shared\Console\Signature\Description as SignatureDescription;
use App\Shared\Console\Signature\Name as SignatureName;
use App\Shared\Console\Signature\Option as SignatureOption;
use App\Shared\Console\Signature\Signature;
use App\Shared\Db\Schema\Provider as SchemaProvider;
use App\Shared\Kernel\AppDir;
use App\Shared\Env\Env;
use App\Shared\Kernel\Discover;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\SchemaProvider as DoctrineSchemaProvider;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
use Exception;
use Generator;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Component\Console\Command\Command as SymfonyCommnd;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Xtompie\Container\Container;

final class ApplicationProvider
{
    public function __construct(
        private Discover $discover,
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

    private function symfony(CommandMeta $definition): SymfonyCommnd
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
                    'Migrations' => $appDir->__invoke() . '/src/Shared/Db/Migrations',
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
     * @return Generator<class-string>
     */
    private function classes(): Generator
    {
        yield from Console::commands();
        yield from $this->discover->classes(implements: Command::class, suffix: 'Command');
    }

    /**
     * @return Generator<CommandMeta>
     */
    private function commands(): Generator
    {
        $unique = [];
        foreach ($this->classes() as $class) {
            if (isset($unique[$class])) {
                continue;
            }
            $unique[$class] = true;
            if (!class_exists($class)) {
                return throw new \InvalidArgumentException('Command must be a valid class-string.');
            }
            $command = $this->commandUsingStatic($class);
            if (!$command) {
                $command = $this->commandUsingAttributes($class);
            }
            if (!$command) {
                throw new Exception("Command $class cannot be resolved using meta or attributes.");
            }
            yield $command;
        }
    }

    private function commandUsingStatic(string $class): ?CommandMeta
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->implementsInterface(CommandWithMeta::class)) {
            return null;
        }

        $command = $class::commandMeta();
        if (!$command instanceof CommandMeta) {
            return null;
        }

        $command->setCommand($class);

        return $command;
    }

    private function commandUsingAttributes(string $class): ?CommandMeta
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);

        $name = null;
        $description = null;
        $arguments = [];
        $options = [];

        foreach ($reflectionClass->getAttributes(Signature::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $attr = $attribute->newInstance();
            if ($attr instanceof SignatureName) {
                $name = (string) $attr;
            }
            if ($attr instanceof SignatureDescription) {
                $description = (string) $attr;
            }
            if ($attr instanceof SignatureArgument) {
                $arguments[] = $attr->toArgument();
            }
            if ($attr instanceof SignatureOption) {
                $options[] = $attr->toOption();
            }
        }

        if ($name === null) {
            return null;
        }

        return new CommandMeta(
            name: $name,
            command: $class,
            description: $description,
            arguments: $arguments,
            options: $options,
        );
    }
}
