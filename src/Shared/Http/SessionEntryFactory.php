<?php

declare(strict_types=1);

namespace App\Shared\Http;

class SessionEntryFactory
{
    public function __construct(
        private Session $session,
    ) {
    }

    public function __invoke(string $property): SessionEntry
    {
        return new SessionEntry(
            session: $this->session,
            property: $property
        );
    }
}
