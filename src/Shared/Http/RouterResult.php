<?php

declare(strict_types=1);

namespace App\Shared\Http;

final readonly class RouterResult
{
    /**
     * @param class-string<Object> $controller
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        public string $controller,
        public array $parameters,
    ) {
    }
}
