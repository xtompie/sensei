<?php

declare(strict_types=1);

namespace App\Sentry\System;

interface Voter
{
    public function __invoke(Rid $rid): bool;
}
