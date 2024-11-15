<?php

namespace App\Shared\Aop;

use ReflectionAttribute;
use ReflectionMethod;

final class Aop
{
    /**
     * @var array<string,array<Advice>>
     */
    protected static array $advices = [];

    /**
     * @template T
     * @param string $method
     * @param array<mixed,mixed> $args
     * @param callable():T $main
     * @param array<Advice> $prepend
     * @param array<Advice> $append
     * @return T
     */
    public static function aop(string $method, array $args, callable $main, array $prepend = [], array $append = []): mixed
    {
        return (new Invocation(
            advices: static::advices(
                method: $method,
                prepend: $prepend,
                append: $append,
            ),
            method: $method,
            args: $args,
            main: $main,
        ))();
    }

    /**
     * @param string $method
     * @param array<Advice> $prepend
     * @param array<Advice> $append
     * @return array<Advice>
     */
    private static function advices(string $method, array $prepend, array $append): array
    {
        if (!array_key_exists($method, static::$advices)) {
            [$className, $methodName] = explode('::', $method);
            $reflection = new ReflectionMethod($className, $methodName);
            $advices = $reflection->getAttributes(Advice::class, ReflectionAttribute::IS_INSTANCEOF);
            $advices = array_map(fn ($attribute) => $attribute->newInstance(), $advices);
            static::$advices[$method] = $advices;
        }

        return array_merge($prepend, static::$advices[$method], $append);
    }
}
