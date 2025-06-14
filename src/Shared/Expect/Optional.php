<?php

declare(strict_types=1);

namespace App\Shared\Expect;

use App\Shared\Dig\Dig;

final class Optional
{
    /**
     * @param array<string|int> $path
     * @return array<mixed>|null
     */
    public static function array(mixed $data, array $path): ?array
    {
        $result = Dig::dig($data, $path);
        return is_array($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     * @return array<int, int>|null
     */
    public static function arrayIntInt(mixed $data, array $path): ?array
    {
        $result = self::array($data, $path);
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
    public static function arrayIntMixed(mixed $data, array $path): ?array
    {
        $result = self::array($data, $path);
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
    public static function arrayIntString(mixed $data, array $path): ?array
    {
        $result = self::array($data, $path);
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
    public static function arrayStringInt(mixed $data, array $path): ?array
    {
        $result = self::array($data, $path);
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
    public static function arrayStringMixed(mixed $data, array $path): ?array
    {
        $result = self::array($data, $path);
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
    public static function arrayStringString(mixed $data, array $path): ?array
    {
        $result = self::array($data, $path);
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
    public static function bool(mixed $data, array $path): ?bool
    {
        $result = Dig::dig($data, $path);
        return is_bool($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     */
    public static function float(mixed $data, array $path): ?float
    {
        $result = Dig::dig($data, $path);
        return is_float($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     */
    public static function int(mixed $data, array $path): ?int
    {
        $result = Dig::dig($data, $path);
        return is_int($result) ? $result : null;
    }

    /**
     * @param array<string|int> $path
     */
    public static function string(mixed $data, array $path): ?string
    {
        $result = Dig::dig($data, $path);
        return is_string($result) ? $result : null;
    }
}
