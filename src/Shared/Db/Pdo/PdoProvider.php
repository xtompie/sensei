<?php

declare(strict_types=1);

namespace App\Shared\Db\Pdo;

use App\Shared\Env\Env;
use PDO;
use Xtompie\Container\Container;
use Xtompie\Container\Provider;

class PdoProvider implements Provider
{
    public static function provide(string $abstract, Container $container): object
    {
        $env = $container->get(Env::class);
        return new PDO(
            'mysql:'
                . 'host=' . $env->APP_DB_HOST() . ';'
                . 'dbname=' . $env->APP_DB_NAME() . ';'
                . 'charset=utf8mb4;',
            $env->APP_DB_USER(),
            $env->APP_DB_PASS(),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );
    }
}
