<?php

declare(strict_types=1);

namespace App\Shared\Gen;

use Ramsey\Uuid\Uuid;

class Gen
{
    public static function id(): string
    {
        return Uuid::uuid4()->__toString();
    }

    public static function token(): string
    {
        return Uuid::uuid4()->__toString();
    }

    public static function secret(): string
    {
        return Uuid::uuid4()->__toString();
    }

    public static function uuid4(): string
    {
        return Uuid::uuid4()->__toString();
    }
}
