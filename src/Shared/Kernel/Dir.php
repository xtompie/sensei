<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

class Dir
{
    public static function ensure(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }

    public static function ensureForFile(string $file): void
    {
        self::ensure(dirname($file));
    }
}
