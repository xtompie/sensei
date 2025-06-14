<?php

declare(strict_types=1);

namespace App\Shared\Dig;

final class Dig
{
    /**
     * Extracts nested data from mixed structures (arrays and objects) using a path.
     *
     * Simple example:
     * ```
     * $data = ['user' => ['name' => 'Anna']];
     * Dig::dig($data, ['user', 'name']); // 'Anna'
     * ```
     *
     * Advanced example (supports array index, object property, and method call):
     * ```
     * $data = [
     *     'items' => [
     *         (object)[
     *             'product' => new class {
     *                 public function getPrice() {
     *                     return 99;
     *                 }
     *             }
     *         ]
     *     ]
     * ];
     * Dig::dig($data, ['items', 0, 'product', 'getPrice()']); // 99
     * ```
     *
     * @param array<string|int> $path
     */
    public static function dig(mixed $data, array $path): mixed
    {
        foreach ($path as $segment) {
            if (is_string($segment) && str_ends_with($segment, '()')) {
                $method = substr($segment, 0, -2);
                if (is_object($data) && method_exists($data, $method)) {
                    $data = $data->$method();
                    continue;
                }
                return null;
            }

            if (is_array($data) && array_key_exists($segment, $data)) {
                $data = $data[$segment];
                continue;
            }

            if (is_object($data) && isset($data->{$segment})) {
                $data = $data->{$segment};
                continue;
            }

            return null;
        }

        return $data;
    }
}
