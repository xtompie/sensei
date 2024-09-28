<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\Response;

class DefineController
{
    public static function controller(): Controller
    {
        return new Controller(path: '/example/define');
    }

    public function __invoke(): Response
    {
        return Response::html('<h1>DefineController</h1>');
    }
}
