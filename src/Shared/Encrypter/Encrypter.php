<?php

declare(strict_types=1);

namespace App\Shared\Encrypter;

use App\Shared\Secret\Secret;

class Encrypter
{
    public function __construct(
        private Secret $secret,
    ) {
    }

    public function encrypt(mixed $data): ?string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $key = sodium_crypto_generichash($this->secret->__invoke(), '', SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $serialized = serialize($data);

        $encrypted = sodium_crypto_secretbox($serialized, $nonce, $key);
        sodium_memzero($key);

        return base64_encode($nonce . $encrypted);
    }

    public function decrypt(string $encrypted): mixed
    {
        $decoded = base64_decode($encrypted, true);
        if ($decoded === false) {
            return null;
        }

        $nonce = substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $key = sodium_crypto_generichash($this->secret->__invoke(), '', SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $decrypted = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
        sodium_memzero($key);

        return $decrypted === false ? null : unserialize($decrypted);
    }
}
