<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

enum ImagePreset: string
{
    case S = 's';
    case M = 'm';
    case L = 'l';

    public static function collection(): ImagePresetCollection
    {
        return new ImagePresetCollection(static::cases());
    }

    public function width(): int
    {
        return match ($this) {
            self::S => 300,
            self::M => 480,
            self::L => 1080,
        };
    }

    public function height(): int
    {
        return match ($this) {
            self::S => 300,
            self::M => 480,
            self::L => 1080,
        };
    }
}
