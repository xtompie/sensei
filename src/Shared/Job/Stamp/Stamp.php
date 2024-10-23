<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

interface Stamp
{
    public static function fromPrimitive(stdClass $primitive): static;

    public function toPrimitive(): stdClass;
}
