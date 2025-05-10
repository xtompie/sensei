<?php

declare(strict_types=1);

namespace App\Shared\Dig;

use InvalidArgumentException;

final class Dig
{
    /**
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

    /**
     * @param array<string|int> $path
     * @return array<mixed>|null
     */
    public static function optionalArray(mixed $data, array $path): ?array
    {
        $result = self::dig($data, $path);
        return is_array($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, int>|null
     */
    public static function optionalArrayIntInt(mixed $data, array $path): ?array
    {
        $result = self::optionalArray($data, $path);
        if ($result !== null) {
            foreach ($result as $key => $value) {
                if (!is_int($key) || !is_int($value)) {
                    return null;
                }
            }
            /** @var array<int, int> $result */
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, mixed>|null
     */
    public static function optionalArrayIntMixed(mixed $data, array $path): ?array
    {
        $result = self::optionalArray($data, $path);
        if ($result !== null) {
            foreach ($result as $key => $_) {
                if (!is_int($key)) {
                    return null;
                }
            }
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, string>|null
     */
    public static function optionalArrayIntString(mixed $data, array $path): ?array
    {
        $result = self::optionalArray($data, $path);
        if ($result !== null) {
            foreach ($result as $key => $value) {
                if (!is_int($key) || !is_string($value)) {
                    return null;
                }
            }
            /** @var array<int, string> $result */
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, int>|null
     */
    public static function optionalArrayStringInt(mixed $data, array $path): ?array
    {
        $result = self::optionalArray($data, $path);
        if ($result !== null) {
            foreach ($result as $key => $value) {
                if (!is_string($key) || !is_int($value)) {
                    return null;
                }
            }
            /** @var array<string, int> $result */
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, mixed>|null
     */
    public static function optionalArrayStringMixed(mixed $data, array $path): ?array
    {
        $result = self::optionalArray($data, $path);
        if ($result !== null) {
            foreach ($result as $key => $_) {
                if (!is_string($key)) {
                    return null;
                }
            }
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, string>|null
     */
    public static function optionalArrayStringString(mixed $data, array $path): ?array
    {
        $result = self::optionalArray($data, $path);
        if ($result !== null) {
            foreach ($result as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    return null;
                }
            }
            /** @var array<string, string> $result */
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function optionalBool(mixed $data, array $path): ?bool
    {
        $result = self::dig($data, $path);
        return is_bool($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     */
    public static function optionalFloat(mixed $data, array $path): ?float
    {
        $result = self::dig($data, $path);
        return is_float($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     */
    public static function optionalInt(mixed $data, array $path): ?int
    {
        $result = self::dig($data, $path);
        return is_int($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     */
    public static function optionalString(mixed $data, array $path): ?string
    {
        $result = self::dig($data, $path);
        return is_string($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     * @return array<mixed>
     */
    public static function requireArray(mixed $data, array $path): array
    {
        $result = self::optionalArray($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, int>
     */
    public static function requireArrayIntInt(mixed $data, array $path): array
    {
        $result = self::optionalArrayIntInt($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<int, int> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, mixed>
     */
    public static function requireArrayIntMixed(mixed $data, array $path): array
    {
        $result = self::optionalArrayIntMixed($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<int, mixed> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, string>
     */
    public static function requireArrayIntString(mixed $data, array $path): array
    {
        $result = self::optionalArrayIntString($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<int, string> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, int>
     */
    public static function requireArrayStringInt(mixed $data, array $path): array
    {
        $result = self::optionalArrayStringInt($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<string, int> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, mixed>
     */
    public static function requireArrayStringMixed(mixed $data, array $path): array
    {
        $result = self::optionalArray($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<string, mixed> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     * @return array<string, string>
     */
    public static function requireArrayStringString(mixed $data, array $path): array
    {
        $result = self::optionalArrayStringString($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected array<string, string> at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function requireBool(mixed $data, array $path): bool
    {
        $result = self::optionalBool($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected bool at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function requireFloat(mixed $data, array $path): float
    {
        $result = self::optionalFloat($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected float at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function requireInt(mixed $data, array $path): int
    {
        $result = self::optionalInt($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected int at the given path.');
        }
        return $result;
    }

    /**
     * @param array<string|int> $path
     */
    public static function requireString(mixed $data, array $path): string
    {
        $result = self::optionalString($data, $path);
        if ($result === null) {
            throw new InvalidArgumentException('Expected string at the given path.');
        }
        return $result;
    }
}
