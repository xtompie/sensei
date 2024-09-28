<?php

declare(strict_types=1);

namespace App\Registry;

use Generator;

class Http
{
    /**
     * @return Generator<class-string>
     */
    public static function controllers(): Generator
    {
        yield from \App\Example\Registry\Http::controllers();
    }
}
