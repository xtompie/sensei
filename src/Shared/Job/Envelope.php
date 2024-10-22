<?php

declare(strict_types=1);

namespace App\Shared\Job;

final class Envelope
{
    public static function fromSerialization(string $serialization): ?static
    {
        try {
            $primitive = json_decode($serialization, true, 512, JSON_THROW_ON_ERROR);
            if (!isset($primitive['job'])) {
                return null;
            }

            $job = unserialize($primitive['job']);
            if ($job === false) {
                return null;
            }

            $stamps = [];
            if (isset($primitive['stamps']) && is_array($primitive['stamps'])) {
                foreach ($primitive['stamps'] as $stampClass => $stampPrimitives) {
                    if (class_exists($stampClass) && is_array($stampPrimitives)) {
                        foreach ($stampPrimitives as $stampPrimitive) {
                            $stamps[$stampClass][] = $stampClass::fromPrimitive($stampPrimitive);
                        }
                    } else {
                        return null;
                    }
                }
            }

            return new static($job, $stamps);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param array<class-string<Stamp>, list<Stamp>> $stamps
     */
    public function __construct(
        private object $job,
        private array $stamps = [],
    ) {
    }

    public function job(): object
    {
        return $this->job;
    }

    public function add(Stamp $stamp): void
    {
        $this->stamps[$stamp::class][] = $stamp;
    }

    /**
     * @return array<class-string<Stamp>, list<Stamp>>
     */
    public function stamps(): array
    {
        return $this->stamps;
    }

    /**
     * @param class-string<Stamp> $stamp
     */
    public function last(string $stamp): ?Stamp
    {
        return isset($this->stamps[$stamp]) ? end($this->stamps[$stamp]) : null;
    }

    /**
     * @param class-string<Stamp> $stamp
     */
    public function has(string $stamp): bool
    {
        return isset($this->stamps[$stamp]);
    }

    public function toSerialization(): string
    {
        return json_encode(
            value:  [
                'job' => serialize($this->job),
                'stamps' => array_map(
                    callback: fn (array $stamps) => array_map(
                        callback: fn (Stamp $stamp) => $stamp->toPrimitive(),
                        array: $stamps
                    ),
                    array: $this->stamps
                ),
            ],
            flags: JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
        );
    }
}
