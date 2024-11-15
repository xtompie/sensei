<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Path implements Route
{
    /**
     * @param string $path
     * @param array<string,string> $requirements
     */
    public function __construct(
        public string $path,
        public array $requirements = [],
    ) {
    }
}
