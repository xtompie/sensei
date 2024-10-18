<?php

declare(strict_types=1);

namespace App\Shared\Console\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Console\Output;
use Attribute;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

#[Attribute(Attribute::TARGET_METHOD)]
class Lock implements Advice
{
    public function __construct(
        private bool $error = true,
        private ?string $resource = null,
    ) {
    }

    public function __invoke(Invocation $invocation, Output $output): mixed
    {
        $store = new FlockStore();
        $factory = new LockFactory($store);
        $lock = $factory->createLock($this->resource ?? $invocation->method());
        if (!$lock->acquire()) {
            if ($this->error) {
                $output->errorln('The script is already running in another process.');
                return false;
            } else {
                $output->infoln('The script is already running in another process.');
                return true;
            }
        }

        $result = $invocation();
        $lock->release();
        return $result;
    }
}
