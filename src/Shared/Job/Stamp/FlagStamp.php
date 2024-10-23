<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

abstract class FlagStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new self();
    }

    public function toPrimitive(): stdClass
    {
        return new stdClass();
    }
}
