<?php

declare(strict_types=1);

namespace App\Shared\Console;

interface CommandWithMeta
{
    public static function commandMeta(): CommandMeta;
}
