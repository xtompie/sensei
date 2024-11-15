<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\ControllerDefinition;
use App\Shared\Http\HasControllerDefinition;
use App\Shared\Http\Response;

class DefinitionController implements Controller, HasControllerDefinition
{
    public static function controllerDefinition(): ControllerDefinition
    {
        return new ControllerDefinition(path: '/example/definition');
    }

    public function __invoke(): Response
    {
        return Response::html('<h1>DefinitionController</h1>');
    }
}
