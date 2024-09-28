<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Shared\Container\Container;
use App\Shared\Http\Kernel as HttpKernel;
use App\Shared\Kernel\Kernel as BaseKernel;

Container::container()->get(BaseKernel::class)->__invoke(appDir: dirname(__DIR__));
Container::container()->get(HttpKernel::class)->__invoke();
