<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

interface Method extends Route
{
    public function __toString(): string;
}
