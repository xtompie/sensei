<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class ArrayType implements Type
{
    public function __construct(
        public Type $items
    ) {
    }
}
