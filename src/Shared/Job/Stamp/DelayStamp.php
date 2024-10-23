<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

class DelayStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            delay: $primitive->delay,
        );
    }

    public function __construct(
        private int $delay,
    ) {
    }

    public function delay(): int
    {
        return $this->delay;
    }

    public function toPrimitive(): stdClass
    {
        return (object)[
            'delay' => $this->delay,
        ];
    }
}
