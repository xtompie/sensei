<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

class TypeStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            type: $primitive->type,
        );
    }

    /**
     * @param class-string $type
     */
    public function __construct(
        private string $type,
    ) {
    }

    /**
     * @return class-string
     */
    public function type(): string
    {
        return $this->type;
    }

    public function toPrimitive(): stdClass
    {
        return (object)[
            'type' => $this->type,
        ];
    }
}
