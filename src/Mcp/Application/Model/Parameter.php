<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class Parameter
{
    public function __construct(
        public string $name,
        public Type $type,
        public bool $required
    ) {
    }
}
