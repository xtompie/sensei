<?php

declare(strict_types=1);

namespace App\Backend\System\Flash;

use App\Shared\Http\Flash as BaseFlash;
use App\Shared\Http\SessionEntry;
use App\Shared\Http\SessionEntryFactory;

class Flash extends BaseFlash
{
    protected SessionEntry $sessionEntry;

    public function __construct(
        SessionEntryFactory $sessionEntryFactory,
    ) {
        $this->sessionEntry = $sessionEntryFactory->__invoke('backend.flash');
    }
}
