<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Job\Stamp\SyncStamp;
use App\Shared\Job\Transport\Transport;

class Dispatcher
{
    public function __construct(
        private Transport $transport,
        private Executor $executor,
    ) {
    }

    /**
     * @param object $job
     * @param array<Stamp> $stamps
     * @return Envelope
     */
    public function __invoke(object $job, array $stamps = []): Envelope
    {
        return $this->dispatch(job: $job, stamps: $stamps);
    }

    /**
     * @param object $job
     * @param array<Stamp> $stamps
     * @return Envelope
     */
    public function dispatch(object $job, array $stamps = []): Envelope
    {
        return $this->dispatchEnvelope(new Envelope($job, $stamps));
    }

    public function dispatchEnvelope(Envelope $envelope): Envelope
    {
        $envelope = EnvelopeDefaults::defaults($envelope);

        if (!$envelope->has(SyncStamp::class)) {
            return $this->executor->execute($envelope);
        } else {
            return $this->transport->send($envelope);
        }
    }
}
