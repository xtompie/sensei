<?php

namespace App\Shared\Silent;

use Throwable;

final class Silent
{
    /**
     * @param callable $callback
     */
    public function __construct(
        private $callback,
    ) {
    }

    public function __invoke(): mixed
    {
        try {
            return ($this->callback)();
        } catch (Throwable $e) {
            error_log(
                'Silent exception: ' . $e->getMessage() . ' ' .
                'in ' . $e->getFile() . ':' . $e->getLine() . ' ' .
                "Stack trace:\n" . $e->getTraceAsString()
            );
            return null;
        }
    }

    public static function silent(callable $callback): mixed
    {
        return (new self($callback))();
    }
}
