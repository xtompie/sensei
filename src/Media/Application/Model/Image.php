<?php

declare(strict_types=1);

namespace App\Media\Application\Model;

use App\Shared\Container\Container;
use App\Shared\Kernel\DataDir;
use InvalidArgumentException;
use SplFileInfo;

final class Image
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
        if (count($parts) !== 3) {
            return false;
        }

        $extension = strtolower(end($parts));
        if (ImageExtension::tryFrom($extension) === null) {
            return false;
        }

        return true;
    }

    private static function storage(): DataDir
    {
        return Container::container()->get(DataDir::class);
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

    public function file(): SplFileInfo
    {
        return new SplFileInfo($this->path());
    }

    public function variants(): ImageVariantCollection
    {
        return ImagePreset::cases()->to(
            class: ImageVariantCollection::class,
            map: fn (ImagePreset $preset) => $this->variant($preset)
        );
    }

    private function variant(ImagePreset $preset): ImageVariant
    {
        $parts = explode('.', $this->id);
        $variant = $parts[0] . '.' . $parts[1] . '.' . $preset->value() . '.' . $parts[2];
        return new ImageVariant($variant);
    }
}
