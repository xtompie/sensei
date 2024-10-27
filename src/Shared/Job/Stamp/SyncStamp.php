<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

final class SyncStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static();
    }

    public function toPrimitive(): stdClass
    {
        return new stdClass();
    }
}
