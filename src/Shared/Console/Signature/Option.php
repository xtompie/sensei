<?php

declare(strict_types=1);

namespace App\Shared\Console\Signature;

use App\Shared\Console\Option as ConsoleOption;

interface Option
{
    public function toOption(): ConsoleOption;
}
