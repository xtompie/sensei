<?php

declare(strict_types=1);

namespace App\Backend\Resource\BackendUser;

use App\Backend\System\Resource\Repository\PaoRepository;

class Repository extends PaoRepository
{
    protected function tenant(): bool
    {
        return true;
    }
}
