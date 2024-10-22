<?php

declare(strict_types=1);

namespace App\Shared\Job;

class QueueStamp implements Stamp
{
    public static function fromPrimitive(array $primitive): static
    {
        return new static(
            queue: new Queue($primitive['queue']),
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

    public function toPrimitive(): array
    {
        return [
            'queue' => $this->queue->value(),
        ];
    }
}
