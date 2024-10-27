<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use stdClass;

final class AvailableAtStamp implements Stamp
{
    public static function ofDelay(int $delay): static
    {
        return new static(
            availableAt: (new DateTime('now', new DateTimeZone('UTC')))->modify("+{$delay} seconds"),
        );
    }

    public static function now(): static
    {
        return new static(
            availableAt: new DateTime('now', new DateTimeZone('UTC')),
        );
    }

    public static function fromPrimitive(stdClass $primitive): static
    {
        $availableAt = DateTime::createFromFormat(DateTime::ATOM, $primitive->availableAt, new DateTimeZone('UTC'));
        if ($availableAt === false) {
            throw new InvalidArgumentException('Invalid availableAt format');
        }
        return new static(
            availableAt: $availableAt,
        );
    }

    public function __construct(
        private DateTime $availableAt,
    ) {
    }

    public function availableAt(): DateTime
    {
        return $this->availableAt;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'availableAt' => $this->availableAt->format(DateTime::ATOM),
        ];
    }
}
