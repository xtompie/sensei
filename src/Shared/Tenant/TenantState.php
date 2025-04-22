<?php

declare(strict_types=1);

namespace App\Shared\Tenant;

class TenantState
{
    public function __construct(
        public ?string $id = null,
    ) {
    }

    public function get(): ?string
    {
        return $this->id;
    }

    public function set(string $id): void
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
