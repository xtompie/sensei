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
    public static function array(mixed $data, array $path): array
    {
        $result = Optional::array($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, int>
     */
    public static function arrayIntInt(mixed $data, array $path): array
    {
        $result = Optional::arrayIntInt($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<int, int> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, mixed>
     */
    public static function arrayIntMixed(mixed $data, array $path): array
    {
        $result = Optional::arrayIntMixed($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<int, mixed> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, string>
     */
    public static function arrayIntString(mixed $data, array $path): array
    {
        $result = Optional::arrayIntString($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<int, string> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, int>
     */
    public static function arrayStringInt(mixed $data, array $path): array
    {
        $result = Optional::arrayStringInt($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<string, int> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, mixed>
     */
    public static function arrayStringMixed(mixed $data, array $path): array
    {
        $result = Optional::array($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<string, mixed> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, string>
     */
    public static function arrayStringString(mixed $data, array $path): array
    {
        $result = Optional::arrayStringString($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<string, string> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function bool(mixed $data, array $path): bool
    {
        $result = Optional::bool($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected bool at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function float(mixed $data, array $path): float
    {
        $result = Optional::float($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected float at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function int(mixed $data, array $path): int
    {
        $result = Optional::int($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected int at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function string(mixed $data, array $path): string
    {
        $result = Optional::string($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected string at the given path.');
        }
        return $result;
    }
}
