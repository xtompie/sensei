<?php

namespace App\Shared\Reliable;

final class Reliable
{
    public function __invoke(callable $callback, int $attempts, int $threshold): bool
    {
        if ($attempts < 1) {
            throw new \InvalidArgumentException('Attempts must be greater than 0.');
        }
        if ($threshold < 1 || $threshold > $attempts) {
            throw new \InvalidArgumentException('Threshold must be between 1 and attempts.');
        }
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Callback must be callable.');
        }

        $successes = 0;

        for ($i = 0; $i < $attempts; $i++) {
            if ($callback() === true) {
                $successes++;
            }
        }

        return $successes >= $threshold;
    }

    public static function reliable(callable $callback, int $attempts, int $threshold): bool
    {
        return (new self())(callback: $callback, attempts: $attempts, threshold: $threshold);
    }
}
