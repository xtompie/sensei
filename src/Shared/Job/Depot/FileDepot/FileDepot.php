<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Job\Depot\Depot;
use App\Shared\Job\Envelope;
use App\Shared\Job\Priority;
use App\Shared\Job\Stamp\AvailableAtStamp;
use App\Shared\Job\Stamp\IdStamp;
use DateTime;
use RuntimeException;

class FileDepot implements Depot
{
    public function __construct(
        private FileDepotDir $dir
    ) {
    }

    public function put(Envelope $envelope): Envelope
    {
        $currentPath = $envelope->get(FileDepotPathStamp::class)?->path();
        $availableAtStamp = $envelope->get(AvailableAtStamp::class);
        if (!$availableAtStamp) {
            throw new RuntimeException('AvailableAtStamp is required');
        }
        $idStamp = $envelope->get(IdStamp::class);
        if (!$idStamp) {
            throw new RuntimeException('IdStamp is required');
        }
        $futurePath = $this->dir->__invoke() . '/todo/' . $this->path(
            time: $availableAtStamp->availableAt(),
            id: $idStamp->id()
        );

        if ($currentPath !== $futurePath) {
            $envelope = $envelope->add(new FileDepotPathStamp($futurePath));
        }

        file_put_contents($futurePath, json_encode($envelope->toSerialization()));

        if ($currentPath && $currentPath !== $futurePath) {
            unlink($currentPath);
        }

        return $envelope;
    }

    public function get(Priority $priority): ?Envelope
    {
        // przeniesienie do work
        return null;
    }

    public function done(Envelope $envelope): Envelope
    {
        // z work usuniecie
    }

    public function fail(Envelope $envelope): Envelope
    {
        // z work do fail
    }

    public function archive(Envelope $envelope): Envelope
    {

    }

    public function path(DateTime $time, string $id): void
    {
        return $time->format('Y/m/d/H/i') . '/' . $time->format('Y_m_d_H_i_s') . '_' . $id . '.json';
    }

}
