<?php

declare(strict_types=1);

namespace App\Shared\Http;

class CsrfEnabled
{
    public function __construct(
        private bool $enabled = true,
    ) {
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function enable(bool $enable): void
    {
        $this->enabled = $enable;
    }
}
