<?php

declare(strict_types=1);

namespace App\Shared\Type;

use Carbon\Carbon;

final class Time
{
    public static function now(): static
    {
        return new static(Carbon::now());
    }

    public static function fromSerialization(string $serialization): static
    {
        return new static(new Carbon($serialization));
    }

    public static function fromPrimitive(string $primitive): static
    {
        return new static(new Carbon($primitive));
    }

    public static function fromDateTimeString(?string $datetime): static
    {
        if (str_starts_with((string) $datetime, '0000')) {
            $datetime = null;
        }
        return new static(new Carbon($datetime));
    }

    public static function fromString(string $datetime): static
    {
        return new static(Carbon::parse($datetime));
    }

    public static function fromTimestamp(int $timestamp): static
    {
        return new static(Carbon::createFromTimestamp($timestamp));
    }

    public function __construct(
        protected Carbon $value,
    ) {
    }

    public function toSerialization(): string
    {
        return $this->value->toDateTimeString();
    }

    public function toPrimitive(): string
    {
        return $this->value->toDateTimeString();
    }

    public function toDateString(): string
    {
        return $this->value->toDateString();
    }

    public function toDateTimeString(): string
    {
        return $this->value->toDateTimeString();
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }

    public function formatDe(): string
    {
        return $this->value->format('d.m.Y');
    }

    public function formatDeWithTime(): string
    {
        return $this->value->format('d.m.Y H:i:s');
    }

    public function day(): string
    {
        return $this->value->format('d');
    }

    public function month(): string
    {
        return $this->value->format('F');
    }

    public function year(): string
    {
        return $this->value->format('Y');
    }

    public function time(): string
    {
        return $this->value->format('H:i:s');
    }

    public function isFuture(): bool
    {
        return $this->value->isFuture();
    }

    public function diffInSeconds(string $date): int
    {
        return intval($this->value->diffInSeconds($date));
    }

    public function addSeconds(int $seconds): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->addSeconds($seconds);
        return $new;
    }

    public function addDays(int $days): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->addDays($days);
        return $new;
    }

    public function addMonths(int $months): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->addMonths($months);
        return $new;
    }

    public function addYears(int $years): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->addYears($years);
        return $new;
    }

    public function subSeconds(int $seconds): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->subSeconds($seconds);
        return $new;
    }

    public function subDays(int $days): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->subDays($days);
        return $new;
    }

    public function subYears(int $years): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->subYears($years);
        return $new;
    }

    public function dayStart(): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->startOfDay();
        return $new;
    }

    public function dayEnd(): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->endOfDay();
        return $new;
    }

    public function monthStart(): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->startOfMonth();
        return $new;
    }

    public function monthEnd(): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->endOfMonth();
        return $new;
    }

    public function yearStart(): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->startOfYear();
        return $new;
    }

    public function yearEnd(): static
    {
        $new = clone $this;
        $new->value = clone $new->value;
        $new->value = $new->value->endOfYear();
        return $new;
    }

    public function gt(Time $time): bool
    {
        return $this->value->gt($time->value);
    }

    public function lt(Time $time): bool
    {
        return $this->value->lt($time->value);
    }

    public function between(Time $time1, Time $time2): bool
    {
        return $this->value->between($time1->value, $time2->value);
    }
}
