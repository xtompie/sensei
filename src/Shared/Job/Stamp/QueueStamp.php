<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use App\Shared\Job\Queue;
use stdClass;

class QueueStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            queue: new Queue($primitive->queue),
        );
    }

    public function __construct(
        private Queue $queue,
    ) {
    }

    public function queue(): Queue
    {
        return $this->queue;
    }

    public function toPrimitive(): stdClass
    {
        return (object)[
            'queue' => $this->queue->value(),
        ];
    }
}
