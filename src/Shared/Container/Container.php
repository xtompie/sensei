<?php

declare(strict_types=1);

namespace App\Shared\Container;

use PDO;
use Xtompie\Container\Container as BaseContainer;

final class Container extends BaseContainer
{
    public function __construct()
    {
        $this->providers = [
            PDO::class => \App\Shared\Db\Pdo\PdoProvider::class,
        ];

        $this->bindings = [
            \Xtompie\Aql\Platform::class => \Xtompie\Aql\MySQLPlatform::class,
            \Xtompie\Dao\Adapter::class => \Xtompie\Dao\PdoAdapter::class,
            \Xtompie\Dao\PdoAdapter::class => \App\Shared\Profiler\PdoAdapter::class,
            \Xtompie\Aql\Aql::class => \App\Shared\Profiler\Aql::class,
        ];
    }
}
