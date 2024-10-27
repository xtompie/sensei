<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use DateTime;
use DateTimeZone;
use stdClass;

final class RetryScheduledStamp implements Stamp
{
    public static function ofDelay(int $delay): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            delay: $delay,
        );
    }

    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            delay: $primitive->delay
        );
    }

    public function __construct(
        private DateTime $at,
        private int $delay,
    ) {
    }

    public function delay(): int
    {
        return $this->delay;
    }

    public function at(): DateTime
    {
        return $this->at;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'at' => $this->at->format('Y-m-d H:i:s'),
            'delay' => $this->delay,
        ];
    }
}
