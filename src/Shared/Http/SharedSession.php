<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Xtompie\Container\Container;
use Xtompie\Container\Provider;

class SharedSession extends Session implements Provider
{
    public static function provide(string $abstract, Container $container): object
    {
        return new SharedSession(
            space: 'shared',
        );
    }
}
