<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

class File
{
    public static function write(string $filename, mixed $data): void
    {
        Dir::ensureForFile($filename);
        file_put_contents($filename, $data);
    }
}
