<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class DELETE implements Method
{
    public function __toString(): string
    {
        return 'DELETE';
    }
}
