<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Container\Container;
use App\Shared\Kernel\CacheDir;
use InvalidArgumentException;
use SplFileInfo;

final class ImageVariant
{
    public static function tryFrom(string $id): ?static
    {
        if (!self::isValidId($id)) {
            return null;
        }

        return new static($id);
    }

    public function __construct(
        protected string $id,
    ) {
        if (!self::isValidId($id)) {
            throw new InvalidArgumentException('Invalid image id');
        }
    }

    private static function isValidId(string $id): bool
    {
        $mediaTypePrefix = MediaType::image()->value() . '/';
        if (strpos($id, $mediaTypePrefix) !== 0) {
            return false;
        }

        $parts = explode('.', $id);
        if (count($parts) !== 4) {
            return false;
        }

        if (ImagePreset::tryFrom($parts[2]) === null) {
            return false;
        }

        $extension = strtolower(end($parts));
        if (ImageExtension::tryFrom($extension) === null) {
            return false;
        }

        return true;
    }

    private static function storage(): CacheDir
    {
        return Container::container()->get(CacheDir::class);
    }

    private static function pathPrefix(): string
    {
        static $dir;
        if ($dir === null) {
            $dir = static::storage()->__invoke();
        }
        return "$dir/media";
    }

    public function id(): string
    {
        return $this->id;
    }

    public function path(): string
    {
        return static::pathPrefix() . '/' . $this->id;
    }

    public function url(): string
    {
        return '/media/' . $this->id;
    }

    public function file(): SplFileInfo
    {
        return new SplFileInfo($this->path());
    }

    public function preset(): ImagePreset
    {
        $parts = explode('.', $this->id);
        return new ImagePreset($parts[2]);
    }

    public function image(): Image
    {
        $parts = explode('.', $this->id);
        return new Image($parts[0] . '.' . $parts[1] . '.' . $parts[3]);
    }
}
