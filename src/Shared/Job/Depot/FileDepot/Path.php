<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Job\Envelope;
use App\Shared\Job\Stamp\AvailableAtStamp;
use App\Shared\Job\Stamp\IdStamp;
use App\Shared\Job\Stamp\PriorityStamp;
use RuntimeException;

class Path
{
    public function __invoke(string $stage, Envelope $envelope): string
    {
        $availableAtStamp = $envelope->get(AvailableAtStamp::class);
        if (!$availableAtStamp) {
            throw new RuntimeException('AvailableAtStamp is required');
        }
        $idStamp = $envelope->get(IdStamp::class);
        if (!$idStamp) {
            throw new RuntimeException('IdStamp is required');
        }
        $priorityStamp = $envelope->get(PriorityStamp::class);
        if (!$priorityStamp) {
            throw new RuntimeException('PriorityStamp is required');
        }

        return $stage
            . '/' . $priorityStamp->priority()->value()
            . '/' . $availableAtStamp->availableAt()->format('Y/m/d/H/i')
            . '/' . $availableAtStamp->availableAt()->format('Y_m_d_H_i_s') . '_' . $idStamp->id() . '.json'
        ;
    }
}
