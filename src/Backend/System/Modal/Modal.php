<?php

declare(strict_types=1);

namespace App\Backend\System\Modal;

use App\Backend\System\Resource\Selection\Selection;

class Modal
{
    public function __construct(
        private Selection $selection
    ) {
    }

    public function is(): bool
    {
        if ($this->selection->enabled()) {
            return true;
        }

        return false;
    }
}
