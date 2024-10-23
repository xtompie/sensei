<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Container\Container;

class Executor
{
    public function __construct(
    ) {
    }

    public function execute(Envelope $envelope): Envelope
    {
        $job = $envelope->job();

        try {
            $result = Container::container()->call([$job, '__invoke']);
            // zawsze dodac stamp Execution info o execution, optional blad string
            // jak jest ok to dodact HandledStamp

        } catch (\Throwable $e) {

        }



        return $envelope;
    }


}
