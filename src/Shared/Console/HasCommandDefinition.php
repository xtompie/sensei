<?php

declare(strict_types=1);

namespace App\Shared\Console;

interface HasCommandDefinition
{
    public static function commandDefinition(): CommandDefinition;
}
