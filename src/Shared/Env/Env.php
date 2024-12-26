<?php

declare(strict_types=1);

namespace App\Shared\Env;

use Generator;

final class Env
{
    /**
     * @param Loader $loader
     * @param array<string> $envs
     */
    public function __construct(
        private Loader $loader,
        private ?array $envs = null,
    ) {
    }

    public function __invoke(string $key): string
    {
        if ($this->envs === null) {
            $this->envs = $this->loader->__invoke();
        }

        return $this->envs[$key] ?? '';
    }

    /**
     * @return Generator<int, Entry>
     */
    public function entries(): Generator
    {
        yield new Entry('APP_DB_HOST', 'Database host', default: 'localhost');
        yield new Entry('APP_DB_NAME', 'Database name');
        yield new Entry('APP_DB_PASS', 'Database password');
        yield new Entry('APP_DB_USER', 'Database user');
        yield new Entry('APP_DEBUG', 'Debug mode', optional: true, default: '0');
        yield new Entry('APP_KERNEL_WHOOPS_EDITOR', 'Whoops editor: phpstorm, vscode, ...', optional: true, default: 'vscode');
        yield new Entry('APP_MAILER_DSN', 'Mailer DSN', default: 'smtp://127.0.0.1:1025');
        yield new Entry('APP_MAILER_FROM', 'Mailer from', default: 'no-reply@localhost');
        yield new Entry('APP_MAILER_SUBJECT_PREFIX', 'Mailer subject prefix', optional: true, default: '[local] ');
        yield new AppSecretEntry('APP_SECRET', 'Application secret');
    }

    public function APP_DB_HOST(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_DB_NAME(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_DB_PASS(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_DB_USER(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_DEBUG(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_KERNEL_WHOOPS_EDITOR(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_MAILER_DSN(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_MAILER_FROM(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_MAILER_SUBJECT_PREFIX(): string
    {
        return $this(__FUNCTION__);
    }

    public function APP_SECRET(): string
    {
        return $this(__FUNCTION__);
    }
}
