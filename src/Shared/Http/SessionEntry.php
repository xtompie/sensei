<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Tenant\TenantState;
use Exception;

final class SessionEntry
{
    public function __construct(
        private Session $session,
        private TenantState $tenantState,
        private ?string $property = null,
    ) {
    }

    public function withProperty(string $property): static
    {
        $new = clone $this;
        $new->property = $property;
        return $new;
    }

    private function property(): string
    {
        if ($this->property === null) {
            throw new Exception('Property is not set');
        }
        return 'tenant.' . $this->tenantState->__toString() . '.' . $this->property;
    }

    public function set(mixed $value): void
    {
        $this->session->set($this->property(), $value);
    }

    public function get(): mixed
    {
        return $this->session->get($this->property());
    }

    public function getAsString(): ?string
    {
        $value = $this->get();
        return is_string($value) ? $value : null;
    }

    public function remove(): void
    {
        $this->session->remove($this->property());
    }

    public function add(mixed $item): void
    {
        $current = $this->get();
        if (!is_array($current)) {
            $current = [];
        }
        $current[] = $item;
        $this->set($current);
    }

    public function pull(): mixed
    {
        $value = $this->get();
        $this->remove();
        return $value;
    }
}
