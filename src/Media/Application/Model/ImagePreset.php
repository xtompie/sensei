<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Type\EnumIdCases;

/**
 * @extends EnumIdCases<ImagePreset>
 */
final class ImagePreset extends EnumIdCases
{
    protected static array $valid = [
        's',
        'm',
        'l',
    ];

    /** @var class-string<ImagePresetCollection> */
    protected static string $collection = ImagePresetCollection::class;

    public static function s(): static
    {
        return new static(__FUNCTION__);
    }

    public static function m(): static
    {
        return new static(__FUNCTION__);
    }

    public static function l(): static
    {
        return new static(__FUNCTION__);
    }

    public function width(): int
    {
        return match ($this->value) {
            's' => 300,
            'm' => 480,
            'l' => 1080,
            default => throw new \InvalidArgumentException('Invalid preset'),
        };
    }

    public function height(): int
    {
        return match ($this->value) {
            's' => 300,
            'm' => 480,
            'l' => 1080,
            default => throw new \InvalidArgumentException('Invalid preset'),
        };
    }
}
