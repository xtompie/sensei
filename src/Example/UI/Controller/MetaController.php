<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\ControllerMeta;
use App\Shared\Http\ControllerWithMeta;
use App\Shared\Http\Response;

class MetaController implements Controller, ControllerWithMeta
{
    public static function controllerMeta(): ControllerMeta
    {
        return new ControllerMeta(path: '/example/meta');
    }

    public function __invoke(): Response
    {
        return Response::html('<h1>MetaController</h1>');
    }
}
