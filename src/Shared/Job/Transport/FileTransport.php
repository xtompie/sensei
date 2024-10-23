<?php

declare(strict_types=1);

namespace App\Shared\Job\Transport;

use App\Shared\Job\Envelope;
use App\Shared\Job\Queue;

class FileTransport implements Transport
{
    public function send(Envelope $envelope): void
    {

    }

    public function get(Queue $queue): ?Envelope
    {
        return null;
    }

    public function ack(Envelope $envelope): void
    {

    }

    public function nack(Envelope $envelope): void
    {

    }
}

