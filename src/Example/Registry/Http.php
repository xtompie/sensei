<?php

declare(strict_types=1);

namespace App\Example\Registry;

use Generator;

class Http
{
    /**
     * @return Generator<class-string>
     */
    public static function controllers(): Generator
    {
        yield \App\Example\UI\Controller\TplController::class;
        yield \App\Example\UI\Controller\TypedController::class;
        yield \App\Example\UI\Controller\StringController::class;
        yield \App\Example\UI\Controller\DefineController::class;
    }
}
