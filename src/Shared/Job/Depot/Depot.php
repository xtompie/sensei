<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot;

use App\Shared\Job\Envelope;
use App\Shared\Job\Priority;

interface Depot
{
    public function put(Envelope $envelope): Envelope;

    public function get(Priority $priority): ?Envelope;

    public function done(Envelope $envelope): Envelope;

    public function fail(Envelope $envelope): Envelope;

    public function archive(Envelope $envelope): Envelope;
}
