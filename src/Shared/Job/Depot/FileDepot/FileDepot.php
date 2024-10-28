<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Gen\Gen;
use App\Shared\Job\Depot\Depot;
use App\Shared\Job\Envelope;
use App\Shared\Job\Priority;
use Generator;

class FileDepot implements Depot
{
    public function __construct(
        private Fetch $fetch,
        private Store $store,
    ) {
    }

    public function put(Envelope $envelope): Envelope
    {
        return $this->store->__invoke('todo', $envelope);
    }

    /**
     * @return Generator<Envelope>
     */
    public function get(): Generator
    {
        foreach ($this->fetch->__invoke() as $envelope) {
            $envelope = $this->store->__invoke('work', $envelope);
            yield $envelope;

        }
    }

    public function done(Envelope $envelope): Envelope
    {
        return $this->store->__invoke('done', $envelope);
    }

    public function fail(Envelope $envelope): Envelope
    {
        return $this->store->__invoke('fail', $envelope);
    }

    public function archive(Envelope $envelope): Envelope
    {
        return $this->store->__invoke('archive', $envelope);
    }
}
