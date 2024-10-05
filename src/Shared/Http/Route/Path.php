<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Path implements Route
{
    public function __construct(
        public string $path,
    ) {
    }
}
