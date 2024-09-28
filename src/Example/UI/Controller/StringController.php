<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Route\Path;

class StringController
{
    #[Path('/example/string')]
    public function __invoke(): string
    {
        /** @see \App\Shared\Http\Kernel::response() */
        return 'Hello, World!';
    }
}
