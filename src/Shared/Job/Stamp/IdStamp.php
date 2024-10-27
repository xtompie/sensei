<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

final class IdStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            id: $primitive->id,
        );
    }

    public function __construct(
        private string $id,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'id' => $this->id,
        ];
    }
}
