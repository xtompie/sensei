<?php

declare(strict_types=1);

namespace App\Shared\Http;

interface Csrf
{
    public function get(): string;

    public function revoke(): void;

    public function verify(): bool;
}
