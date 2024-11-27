<?php

declare(strict_types=1);

namespace App\Shared\Tenant;

class TenantContext
{
    public function __construct(
        private ?string $id = null,
    ) {
        $this->id = 'default';
    }

    public function id(): string
    {
        if ($this->id === null) {
            throw new \RuntimeException('Tenant ID is not set');
        }
        return $this->id;
    }
}
