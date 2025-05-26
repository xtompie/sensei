<?php

declare(strict_types=1);

namespace App\Shared\Migration;

use App\Shared\Env\Env;
use App\Shared\Kernel\AppDir;
use App\Shared\Schema\Provider as SchemaProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\SchemaProvider as DoctrineSchemaProvider;

class DependencyFactoryProvider
{
    public function __construct(
        private readonly AppDir $appDir,
        private readonly Env $env,
        private readonly SchemaProvider $schemaProvider,
    ) {
    }

    public function __invoke(): DependencyFactory
    {
        $connection = DriverManager::getConnection(
            params: [
                'dbname' => $this->env->APP_DB_NAME(),
                'driver' => 'pdo_mysql',
                'host' => $this->env->APP_DB_HOST(),
                'password' => $this->env->APP_DB_PASS(),
                'user' => $this->env->APP_DB_USER(),
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
                    'Migrations' => $this->appDir->__invoke() . '/tools/migrations',
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
        return $di;
    }
}
