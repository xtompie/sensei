<?php

declare(strict_types=1);

namespace App\Sentry\Application\Model;

interface Voter
{
    public function __invoke(string $sid): bool;
}
