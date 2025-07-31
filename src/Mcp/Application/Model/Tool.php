<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class Tool
{
    /**
     * @param Method[] $methods
     */
    public function __construct(
        public string $id,
        public string $description,
        public array $methods
    ) {
    }

    public function findMethodByName(string $name): ?Method
    {
        foreach ($this->methods as $method) {
            if ($method->name === $name) {
                return $method;
            }
        }
        return null;
    }
}
