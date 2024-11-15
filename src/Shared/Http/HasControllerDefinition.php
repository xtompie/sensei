<?php

declare(strict_types=1);

namespace App\Shared\Http;

interface HasControllerDefinition
{
    public static function controllerDefinition(): ControllerDefinition;
}
