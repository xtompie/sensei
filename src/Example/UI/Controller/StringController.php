<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\Route\Path;

#[Path('/example/string')]
class StringController implements Controller
{
    public function __invoke(): string
    {
        /** @see \App\Shared\Http\Kernel::response() */
        return 'Hello, World!';
    }
}
