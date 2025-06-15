<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

use App\Shared\Http\SessionEntry;
use App\Shared\Http\SessionEntryFactory;

final class LoggedState
{
    private SessionEntry $sessionEntry;

    public function __construct(
        SessionEntryFactory $sessionEntryFactory,
    ) {
        $this->sessionEntry = $sessionEntryFactory->__invoke('backend.system.auth.id');
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
