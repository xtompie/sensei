<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot;

use App\Shared\Job\Envelope;
use Generator;

interface Depot
{
    public function put(Envelope $envelope): Envelope;

    /**
     * @return Generator<Envelope>
     */
    public function get(): Generator;

    public function done(Envelope $envelope): Envelope;

    public function fail(Envelope $envelope): Envelope;

    public function archive(Envelope $envelope): Envelope;
}
