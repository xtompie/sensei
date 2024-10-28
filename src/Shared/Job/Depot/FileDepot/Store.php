<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Job\Envelope;
use App\Shared\Kernel\File;

class Store
{
    public function __construct(
        private Dir $dir,
        private Path $path,
    ) {
    }

    public function __invoke(string $stage, Envelope $envelope): Envelope
    {
        $presentPath = $envelope->get(PathStamp::class)?->path();
        $futurePath = $this->path->__invoke($stage, $envelope);

        if ($presentPath !== $futurePath) {
            $envelope = $envelope->add(new PathStamp($futurePath));
        }

        File::write($this->dir->__invoke() . '/' . $futurePath, $envelope->toSerialization());

        if ($presentPath && $presentPath !== $futurePath) {
            unlink($presentPath);
        }

        return $envelope;
    }
}
