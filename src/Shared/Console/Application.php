<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Xtompie\Container\Container;
use Xtompie\Container\Provider;

class Application extends BaseApplication implements Provider
{
    public static function provide(string $abstract, Container $container): object
    {
        return Container::container()->get(ApplicationProvider::class)->__invoke();
    }
}
