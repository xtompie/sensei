<?php

declare(strict_types=1);

namespace App\Shared\Typed;

use App\Shared\Container\Container;
use Xtompie\Result\ErrorCollection;
use Xtompie\Typed\Typed as XtompieTyped;

final readonly class Typed
{
    /**
     * @param class-string $type
     * @param mixed $input
     * @return object
     */
    public static function typed(string $type, mixed $input): mixed
    {
        return XtompieTyped::typed(
            type: $type,
            input: $input,
            injector: static::injector(),
        );
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @param mixed $input
     * @return T|ErrorCollection
     */
    public static function object(string $type, mixed $input): object
    {
        return XtompieTyped::object(
            type: $type,
            input: $input,
            injector: static::injector(),
        );
    }

    private static function injector(): callable
    {
        static $injector = null;

        if ($injector === null) {
            /**
             * @param class-string<object> $class
             */
            $injector = static function (string $class): object {
                /** @var class-string<object> $class */
                return Container::container()->__invoke($class);
            };
        }

        return $injector;
    }
}
