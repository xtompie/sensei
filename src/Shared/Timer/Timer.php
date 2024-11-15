<?php

declare(strict_types=1);

namespace App\Shared\Timer;

use JsonSerializable;
use Xtompie\Container\Transient;

class Timer implements Transient, JsonSerializable
{
    public static function microtime(): float
    {
        $t = explode(' ', microtime());
        return (float) $t[0] + (float) $t[1];
    }

    public static function new(): static
    {
        return new static();
    }

    public static function launch(): static
    {
        return static::new()->start();
    }

    final public function __construct(
        protected float $start = 0,
    ) {
    }

    public function start(): static
    {
        $this->start = self::microtime();
        return $this;
    }

    public function __toString()
    {
        return (string) $this->get();
    }

    public function get(int $round = 4): float
    {
        return round(self::microtime() - $this->start, $round);
    }

    /**
     * @return array{type:class-string,start:float,time:float}
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => __CLASS__,
            'start' => $this->start,
            'time' => $this->get(),
        ];
    }
}
