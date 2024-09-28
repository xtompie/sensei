<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Path
{
    public function __construct(
        public string $path,
    ) {
    }
}
