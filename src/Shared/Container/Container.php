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
            PDO::class => \App\Shared\Pdo\PdoProvider::class,
        ];

        $this->bindings = [
            \App\Shared\Http\Csrf::class => \App\Shared\Http\CsrfUsingSession::class,
            \App\Shared\Http\Session::class => \App\Shared\Http\SessionState::class,
            \Xtompie\Aql\Platform::class => \Xtompie\Aql\MySQLPlatform::class,
            \Xtompie\Dao\Adapter::class => \Xtompie\Dao\PdoAdapter::class,
            \Xtompie\Dao\PdoAdapter::class => \App\Shared\Profiler\PdoAdapter::class,
            \Xtompie\Aql\Aql::class => \App\Shared\Profiler\Aql::class,
        ];
    }
}
