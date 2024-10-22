<?php

declare(strict_types=1);

namespace App\Shared\Job;

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

