<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Kernel\DataDir;

class Dir
{
    public function __construct(
        private DataDir $dataDir
    ) {
    }

    public function __invoke(): string
    {
        return $this->dataDir->__invoke() . '/job';
    }
}
