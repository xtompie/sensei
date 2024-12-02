<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Shared\Http\SessionProperty;

class LoggedUserIdState
{
    public function __construct(
        private SessionProperty $sessionProperty,
    ) {
        $this->sessionProperty = $sessionProperty->withProperty('backend.system.auth.id');
    }

    public function __invoke(): ?string
    {
        return $this->get();
    }

    public function get(): ?string
    {
        return $this->sessionProperty->getAsString();
    }

    public function set(string $id): void
    {
        $this->sessionProperty->set($id);
    }

    public function remove(): void
    {
        $this->sessionProperty->remove();
    }
}
