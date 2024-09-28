<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

interface Method
{
    public function __toString(): string;
}
