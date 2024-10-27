<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Job\Stamp\Stamp;

class Dispatcher
{
    public function __construct(
        private Evaluator $evaluator,
    ) {
    }

    /**
     * @param array<Stamp> $stamps
     */
    public function __invoke(object $job, array $stamps = []): Envelope
    {
        return $this->evaluator->__invoke(new Envelope($job, $stamps));
    }
}
