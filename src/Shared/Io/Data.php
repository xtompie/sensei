<?php

declare(strict_types=1);

namespace App\Shared\Io;

class Data
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private array $data = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function set(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function get(): array
    {
        return $this->data;
    }
}
