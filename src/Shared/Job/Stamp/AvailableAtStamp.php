<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

class AvailableAtStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            availableAt: $primitive->availableAt,
        );
    }

    public function __construct(
        private int $availableAt,
    ) {
    }

    public function availableAt(): int
    {
        return $this->availableAt;
    }

    public function toPrimitive(): stdClass
    {
        return (object)[
            'availableAt' => $this->availableAt,
        ];
    }
}
