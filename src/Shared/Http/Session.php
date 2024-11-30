<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Tenant\TenantContext;

final class Session
{
    public function __construct(
        private TenantContext $tenantContext
    ) {
    }

    private function activate(): void
    {
        if ($this->active()) {
            $this->tenant();
            return;
        }
        session_start();
        $this->tenant();
    }

    public function active(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function set(string $module, string $property, mixed $value): void
    {
        $this->activate();
        $_SESSION[$module][$property] = $value;
    }

    public function get(string $module, string $property): mixed
    {
        $this->activate();
        return $_SESSION[$module][$property] ?? null;
    }

    public function has(string $module, string $property): bool
    {
        $this->activate();
        return isset($_SESSION[$module][$property]);
    }

    public function remove(string $module, string $property): void
    {
        $this->activate();
        unset($_SESSION[$module][$property]);
        if (empty($_SESSION[$module])) {
            unset($_SESSION[$module]);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(string $module): array
    {
        $this->activate();
        return $_SESSION[$module] ?? [];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function dump(): array
    {
        $this->activate();
        return $_SESSION;
    }

    public function clear(string $module): void
    {
        $this->activate();
        unset($_SESSION[$module]);
    }

    public function regenerateId(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        if ($this->active()) {
            session_unset();
            session_destroy();
        }
        $_SESSION = [];
    }

    private function tenant(): void
    {
        $tenantId = $this->tenantContext->id();
        $currentTenant = $this->get('app', 'tenant');

        if ($currentTenant === null) {
            $this->set('app', 'tenant', $tenantId);
            return;
        }

        if ($currentTenant !== $tenantId) {
            $this->destroy();
        }
    }
}
