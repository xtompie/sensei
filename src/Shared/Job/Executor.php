<?php

declare(strict_types=1);

namespace App\Shared\Job;

class Executor
{
    public function __construct(
        private Transport $transport,
    ) {
    }

    public function execute(Envelope $envelope): void
    {

    }
}
