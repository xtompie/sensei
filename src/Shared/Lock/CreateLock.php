<?php

declare(strict_types=1);

namespace App\Shared\Lock;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

class CreateLock
{
    public function __invoke(string $resource): ?Lock
    {
        $store = new FlockStore();
        $factory = new LockFactory($store);
        $lock = $factory->createLock($resource);
        if (!$lock->acquire()) {
            return null;
        }

        return new Lock($lock);
    }
}
