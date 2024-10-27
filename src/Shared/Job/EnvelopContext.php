<?php

declare(strict_types=1);

namespace App\Shared\Job;

class EnvelopContext
{
    public function __construct(
        private ?Envelope $envelope = null,
    ) {
    }

    public function set(Envelope $envelope): void
    {
        $this->envelope = $envelope;
    }

    public function get(): ?Envelope
    {
        return $this->envelope;
    }

    public function clear(): void
    {
        $this->envelope = null;
    }
}
