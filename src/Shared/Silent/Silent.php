<?php

declare(strict_types=1);

namespace App\Shared\Silent;

use Throwable;

final class Silent
{
    public function __invoke(callable $callback, bool $log = true): mixed
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($log) {
                error_log(
                    'Silent exception: ' . $e->getMessage() . ' ' .
                    'in ' . $e->getFile() . ':' . $e->getLine() . ' ' .
                    "Stack trace:\n" . $e->getTraceAsString()
                );
            }
            return null;
        }
    }

    public static function silent(callable $callback, bool $log = true): mixed
    {
        return (new self())(callback: $callback, log: $log);
    }
}
