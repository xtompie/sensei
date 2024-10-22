<?php

declare(strict_types=1);

namespace App\Shared\Job;

class Dispatch
{
    public function __construct(
        private Transport $transport,
    ) {
    }

    /**
     * @param object $job
     * @param array<Stamp> $stamps
     * @return Envelope
     */
    public function __invoke(object $job, array $stamps = []): Envelope
    {
        $envelope = new Envelope($job, $stamps);

        if (!$envelope->has(QueueStamp::class)) {
            $envelope = $envelope->add(new QueueStamp(queue: Queue::default()));
        }



        return $envelope;
    }
}
