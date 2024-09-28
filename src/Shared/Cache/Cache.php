<?php

declare(strict_types=1);

namespace App\Shared\Cache;

use App\Shared\Kernel\CacheDir;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache
{
    protected ?FilesystemAdapter $adapter = null;
    protected ?string $prefix = null;

    public function __construct(
        protected CacheDir $varCacheDir,
    ) {
    }

    public function withPrefix(?string $prefix): static
    {
        $new = clone $this;
        $new->prefix = $prefix;
        $new->adapter = null;
        return $new;
    }

    protected function adapter(): FilesystemAdapter
    {
        return $this->adapter ?: $this->adapter = new FilesystemAdapter(
            $this->sanitize((string) $this->prefix, '-_A-Za-z0-9', '_'),
            0,
            ($this->varCacheDir)() . '/shared'
        );
    }

    protected function sanitize(string $string, string $allow, string $space): string
    {
        return (string) preg_replace('/[^' . $space . $allow . ']/', $space, $string);
    }

    public function __invoke(mixed $key, int $expiresAfterSeconds, callable $callback): mixed
    {
        return $this->adapter()->get(
            sha1(serialize($key)),
            function (CacheItemInterface $item) use ($expiresAfterSeconds, $callback) {
                $item->expiresAfter($expiresAfterSeconds);
                return $callback();
            }
        );
    }
}
