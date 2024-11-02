<?php

declare(strict_types=1);

namespace App\Shared\Messenger;

interface Priority
{
    public static function priority(): int;
}
