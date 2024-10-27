<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use App\Shared\Job\Priority;
use stdClass;

final class PriorityStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            priority: new Priority($primitive->priority),
        );
    }

    public function __construct(
        private Priority $priority,
    ) {
    }

    public function priority(): Priority
    {
        return $this->priority;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'priority' => $this->priority->value(),
        ];
    }
}
