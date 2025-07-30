<?php

declare(strict_types=1);

namespace App\Shared\Expect;

use InvalidArgumentException;

final class Required
{
    /**
     * @param array<string|int> $path
     * @return array<mixed>
     */
    public static function array(mixed $data): array
    {
        $result = Optional::array($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, int>
     */
    public static function arrayIntInt(mixed $data): array
    {
        $result = Optional::arrayIntInt($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array<int, int> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, mixed>
     */
    public static function arrayIntMixed(mixed $data): array
    {
        $result = Optional::arrayIntMixed($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array<int, mixed> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, string>
     */
    public static function arrayIntString(mixed $data): array
    {
        $result = Optional::arrayIntString($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array<int, string> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, int>
     */
    public static function arrayStringInt(mixed $data): array
    {
        $result = Optional::arrayStringInt($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array<string, int> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, mixed>
     */
    public static function arrayStringMixed(mixed $data): array
    {
        $result = Optional::array($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array<string, mixed> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, string>
     */
    public static function arrayStringString(mixed $data): array
    {
        $result = Optional::arrayStringString($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an array<string, string> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function bool(mixed $data): bool
    {
        $result = Optional::bool($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected a bool at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function float(mixed $data): float
    {
        $result = Optional::float($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected a float at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function int(mixed $data): int
    {
        $result = Optional::int($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected an int at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function string(mixed $data): string
    {
        $result = Optional::string($data);
        if ($result === null) {
            throw new InvalidArgumentException('Expected a string at the given path.');
        }
        return $result;
    }
}
