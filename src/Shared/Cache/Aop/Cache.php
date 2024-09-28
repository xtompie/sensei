<?php

declare(strict_types=1);

namespace App\Shared\Cache\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Cache\Cache as CacheCache;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Cache implements Advice
{
    private ?CacheCache $cache = null;

    public function __construct(
        private int $time = 300,
        private ?string $prefix = null,
    ) {
    }

    public function __invoke(Invocation $invocation, CacheCache $cache): mixed
    {
        if ($this->prefix === null) {
            $this->prefix = $invocation->method();
        }
        if ($this->cache === null) {
            $this->cache = $cache->withPrefix($this->prefix);
        }

        return $this->cache->__invoke(
            key: $invocation->args(),
            expiresAfterSeconds: $this->time,
            callback: fn () => $invocation(),
        );
    }
}
