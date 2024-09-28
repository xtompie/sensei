<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Registry\Console;
use App\Shared\Console\Signature\Argument as AttributeArgument;
use App\Shared\Console\Signature\Description as AttributeDescription;
use App\Shared\Console\Signature\Name as AttributeName;
use App\Shared\Console\Signature\Option as AttributeOption;
use App\Shared\Db\Schema\Provider\Provider;
use App\Shared\Kernel\AppDir;
use App\Shared\Env\Env;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
use Generator;
use ReflectionClass;
use ReflectionNamedType;
use Symfony\Component\Console\Command\Command as SymfonyCommnd;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Xtompie\Container\Container;

final class ApplicationProvider
{
    public static function provide(): Application
    {
        $application = new Application();
        $application->setAutoExit(false);
        foreach (static::commands() as $command) {
            $application->add(static::symfony($command));
        }
        static::doctrine($application);
        return $application;
    }

    private static function symfony(Command $definition): SymfonyCommnd
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

    private static function doctrine(Application $application): void
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
            id: SchemaProvider::class,
            service: static fn () => new Provider()
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
     * @return Generator<Command>
     */
    private static function commands(): Generator
    {
        foreach (Console::commands() as $class) {
            if (!class_exists($class)) {
                return throw new \InvalidArgumentException('Controller must be a valid class-string.');
            }

            $command = static::commandUsingStatic($class);

            if (!$command) {
                $command = static::comamndUsingAttributes($class);
            }

            if ($command === null) {
                continue;
            }

            yield $command;
        }
    }

    private static function commandUsingStatic(string $class): ?Command
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);

        if (!$reflectionClass->hasMethod('command')) {
            return null;
        }

        $method = $reflectionClass->getMethod('command');

        if (!$method->isPublic() || !$method->isStatic()) {
            return null;
        }

        if ($method->getNumberOfRequiredParameters() > 0) {
            return null;
        }

        $returnType = $method->getReturnType();

        if (!$returnType instanceof ReflectionNamedType || $returnType->isBuiltin()) {
            return null;
        }

        $controller = $class::command();

        if (!$controller instanceof Command) {
            return null;
        }

        $controller->setCommand($class);

        return $controller;
    }

    private static function comamndUsingAttributes(string $class): ?Command
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->hasMethod('__invoke')) {
            return null;
        }

        $name = null;
        $description = null;
        $arguments = [];
        $options = [];

        foreach ($reflectionClass->getMethod('__invoke')->getAttributes() as $attribute) {
            $attr = $attribute->newInstance();
            if ($attr instanceof AttributeName) {
                $name = (string) $attr;
            }
            if ($attr instanceof AttributeDescription) {
                $description = (string) $attr;
            }
            if ($attr instanceof AttributeArgument) {
                $arguments[] = $attr->toArgument();
            }
            if ($attr instanceof AttributeOption) {
                $options[] = $attr->toOption();
            }
        }

        if ($name === null) {
            return null;
        }

        return new Command(
            name: $name,
            command: $class,
            description: $description,
            arguments: $arguments,
            options: $options,
        );
    }
}
