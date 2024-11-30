<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Exception;

final class SessionVar
{
    public function __construct(
        private Session $session,
        private ?string $module = null,
        private ?string $property = null,
    ) {
    }

    public function withModule(string $module): static
    {
        $clone = clone $this;
        $clone->module = $module;
        return $clone;
    }

    public function withProperty(string $property): static
    {
        $clone = clone $this;
        $clone->property = $property;
        return $clone;
    }

    public function set(mixed $value): void
    {
        if ($this->module === null) {
            throw new Exception('Module is not set');
        }
        if ($this->property === null) {
            throw new Exception('Property is not set');
        }
        $this->session->set($this->module, $this->property, $value);
    }

    public function get(): mixed
    {
        if ($this->module === null) {
            throw new Exception('Module is not set');
        }
        if ($this->property === null) {
            throw new Exception('Property is not set');
        }
        return $this->session->get($this->module, $this->property);
    }

    public function clear(): void
    {
        if ($this->module === null) {
            throw new Exception('Module is not set');
        }
        if ($this->property === null) {
            throw new Exception('Property is not set');
        }
        $this->session->remove($this->module, $this->property);
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
        $this->clear();
        return $value;
    }
}
