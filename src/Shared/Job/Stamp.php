<?php

declare(strict_types=1);

namespace App\Shared\Job;

interface Stamp
{
    public static function fromPrimitive(array $primitive): static;

    public function toPrimitive(): array;
}
