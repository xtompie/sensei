<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class ObjectType implements Type
{
    /**
     * @param array<string, Type> $properties
     */
    public function __construct(
        public array $properties
    ) {
    }
}
