<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Gen\Gen;
use App\Shared\Job\Stamp\AvailableAtStamp;
use App\Shared\Job\Stamp\DelayStamp;
use App\Shared\Job\Stamp\IdStamp;
use App\Shared\Job\Stamp\QueueStamp;
use App\Shared\Job\Stamp\RetryPolicyStamp;
use App\Shared\Job\Stamp\TypeStamp;

final class EnvelopeDefaults
{
    public static function defaults(Envelope $envelope) : Envelope
    {
        $envelope = static::id($envelope);
        $envelope = static::type($envelope);
        $envelope = static::queue($envelope);
        $envelope = static::delay($envelope);
        $envelope = static::retry($envelope);

        return $envelope;
    }

    private static function id(Envelope $envelope): Envelope
    {
        return $envelope->add(new IdStamp(id: Gen::uuid4()));
    }

    private static function type(Envelope $envelope): Envelope
    {
        return $envelope->add(new TypeStamp(type: $envelope->job()::class));
    }

    private static function queue(Envelope $envelope): Envelope
    {
        if ($envelope->has(QueueStamp::class)) {
            return $envelope;
        }

        return $envelope->add(new QueueStamp(queue: Queue::default()));
    }

    private static function delay(Envelope $envelope): Envelope
    {
        $delay = $envelope->get(DelayStamp::class);
        if (!$delay) {
            return $envelope;
        }

        return $envelope->add(new AvailableAtStamp(availableAt: time() + $delay->delay()));
    }

    private static function retry(Envelope $envelope): Envelope
    {
        if ($envelope->has(RetryPolicyStamp::class)) {
            return $envelope;
        }

        return $envelope->add(new RetryPolicyStamp(delayes: [
            60,
            300,
            900,
            3600,
        ]));
    }
}
