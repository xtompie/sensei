<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

final class AppDir
{
    public function __construct(
        private ?string $dir = null,
    ) {
    }

    public function __invoke(): string
    {
        return $this->get();
    }

    public function get(): string
    {
        if ($this->dir === null) {
            throw new \RuntimeException('AppDir not set');
        }
        return $this->dir;
    }

    public function set(string $dir): void
    {
        $this->dir = $dir;
    }
}
