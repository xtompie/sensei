<?php

declare(strict_types=1);

namespace App\Shared\Lock;

use Symfony\Component\Lock\SharedLockInterface;

class Lock
{
    public function __construct(
        private SharedLockInterface $lock,
    ) {
    }

    public function __invoke(): void
    {
        $this->release();
    }

    public function release(): void
    {
        $this->lock->release();
    }
}
