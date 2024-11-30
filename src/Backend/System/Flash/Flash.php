<?php

declare(strict_types=1);

namespace App\Backend\System\Flash;

use App\Shared\Http\Flash as BaseFlash;
use App\Shared\Http\SessionVar;

class Flash extends BaseFlash
{
    public function __construct(
        protected SessionVar $sessionVar,
    ) {
        $this->sessionVar = $sessionVar->withModule('backend')->withProperty('flash');
    }
}
