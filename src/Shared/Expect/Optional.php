<?php

declare(strict_types=1);

namespace App\Shared\Expect;

final class Optional
{
    /**
     * @return array<mixed>|null
     */
    public static function array(mixed $data): ?array
    {
        return is_array($data) ? $data : null;
    }

    /**
     * @return array<int, int>|null
     */
    public static function arrayIntInt(mixed $data): ?array
    {
        $result = self::array($data);
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
     * @return array<int, mixed>|null
     */
    public static function arrayIntMixed(mixed $data): ?array
    {
        $result = self::array($data);
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
     * @return array<int, string>|null
     */
    public static function arrayIntString(mixed $data): ?array
    {
        $result = self::array($data);
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
     * @return array<string, int>|null
     */
    public static function arrayStringInt(mixed $data): ?array
    {
        $result = self::array($data);
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
     * @return array<string, mixed>|null
     */
    public static function arrayStringMixed(mixed $data): ?array
    {
        $result = self::array($data);
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
     * @return array<string, string>|null
     */
    public static function arrayStringString(mixed $data): ?array
    {
        $result = self::array($data);
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

    public static function bool(mixed $data): ?bool
    {
        return is_bool($data) ? $data : null;
    }

    public static function float(mixed $data): ?float
    {
        return is_float($data) ? $data : null;
    }

    public static function int(mixed $data): ?int
    {
        return is_int($data) ? $data : null;
    }

    public static function string(mixed $data): ?string
    {
        return is_string($data) ? $data : null;
    }
}
