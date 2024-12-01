<?php

declare(strict_types=1);

namespace App\Backend\System\Flash;

use App\Shared\Http\Flash as BaseFlash;
use App\Shared\Http\SessionProperty;

class Flash extends BaseFlash
{
    public function __construct(
        protected SessionProperty $sessionProperty,
    ) {
        $this->sessionProperty = $sessionProperty->withProperty('backend.flash');
    }
}
