<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class Method
{
    /**
     * @param Parameter[] $parameters
     */
    public function __construct(
        public string $name,
        public string $description,
        public \Closure $handler,
        public array $parameters,
        public Type $return
    ) {
    }

    public function call(array $arguments): mixed
    {
        return ($this->handler)(...$arguments);
    }
}
