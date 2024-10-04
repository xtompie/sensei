<?php

declare(strict_types=1);

namespace App\Registry;

use Generator;

class Console
{
    /**
     * @return Generator<class-string>
     */
    public static function commands(): Generator
    {
        yield from [];
    }
}
