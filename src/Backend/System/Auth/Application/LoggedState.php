<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

use App\Shared\Http\SessionEntry;

class LoggedState
{
    public function __construct(
        private SessionEntry $sessionEntry,
    ) {
        $this->sessionEntry = $sessionEntry->withProperty('backend.system.auth.id');
    }

    public function __invoke(): ?string
    {
        return $this->get();
    }

    public function get(): ?string
    {
        return $this->sessionEntry->getAsString();
    }

    public function set(string $id): void
    {
        $this->sessionEntry->set($id);
    }

    public function remove(): void
    {
        $this->sessionEntry->remove();
    }
}
