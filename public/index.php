<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Shared\Container\Container;
use App\Shared\Http\Kernel;
use App\Shared\Kernel\AppDir;

Container::container()->get(AppDir::class)->set(dirname(__DIR__));
Container::container()->get(Kernel::class)->__invoke();
