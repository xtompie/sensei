<?php

declare(strict_types=1);

namespace App\Shared\Registry;

use Generator;

class Console
{
    /**
     * @return Generator<class-string>
     */
    public static function commands(): Generator
    {
        yield \App\Shared\Env\CheckCommand::class;
        yield \App\Shared\Env\SetupCommand::class;
        yield \App\Shared\Optimize\OptimizeCommand::class;
    }
}
