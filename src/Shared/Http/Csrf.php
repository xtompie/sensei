<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Xtompie\Container\Container;
use Xtompie\Container\Provider;

abstract class Csrf implements Provider
{
    public static function provide(string $abstract, Container $container): object
    {
        return $container->get(CsrfUsingCookie::class);
    }

    abstract public function get(): string;

    abstract public function revoke(): void;

    abstract public function verify(): bool;
}
