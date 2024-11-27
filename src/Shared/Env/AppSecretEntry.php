<?php

declare(strict_types=1);

namespace App\Shared\Env;

class AppSecretEntry extends Entry
{
    public function default(): ?string
    {
        return bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
    }
}
