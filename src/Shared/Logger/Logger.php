<?php

declare(strict_types=1);

namespace App\Shared\Logger;

use App\Shared\Kernel\LogDir;

final class Logger
{
    public function __construct(
        protected LogDir $logDir,
        protected string $file = 'main.log',
    ) {
    }

    public function withFile(string $file): static
    {
        $new = clone $this;
        $new->file = $file;
        return $new;
    }

    public function withName(string $name): static
    {
        return $this->withFile($name . '-' . date('Y-m-d') . '.log');
    }

    public function __invoke(string $log): void
    {
        $this->dir();
        $this->write($this->msg($log));
    }

    private function msg(string $log): string
    {
        return date('Y-m-d H:i:s') . ' ' . $log . "\n";
    }

    private function write(string $log): void
    {
        file_put_contents($this->path(), $log, FILE_APPEND);
    }

    private function dir(): void
    {
        $dir = dirname($this->path());
        if (is_dir($dir)) {
            return;
        }
        mkdir($dir, 0777, true);
    }

    private function path(): string
    {
        return $this->logDir->__invoke() . '/' . $this->file;
    }
}
