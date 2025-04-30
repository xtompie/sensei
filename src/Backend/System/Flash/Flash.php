<?php

declare(strict_types=1);

namespace App\Backend\System\Flash;

use App\Shared\Http\Flash as BaseFlash;
use App\Shared\Http\SessionEntry;

class Flash extends BaseFlash
{
    public function __construct(
        protected SessionEntry $sessionEntry,
    ) {
        $this->sessionEntry = $sessionEntry->withProperty('backend.flash');
    }
}
