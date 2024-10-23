<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Job\Stamp\Stamp;

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
                            $stamps[] = $stampClass::fromPrimitive($stampPrimitive);
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

    private object $job;
    private array $stamps = [];

    /**
     * @param list<Stamp> $stamps
     */
    public function __construct(object $job, array $stamps = [])
    {
        $this->job = $job;
        foreach ($stamps as $stamp) {
            $this->stamps[$stamp::class][] = $stamp;
        }
    }

    public function job(): object
    {
        return $this->job;
    }

    public function add(Stamp $stamp): Envelope
    {
        $new = clone $this;
        $new->stamps[$stamp::class][] = $stamp;
        return $new;
    }

    /**
     * @param class-string<Stamp> $stamp
     * @return list<Stamp>
     */
    public function all(string $stamp): array
    {
        return $this->stamps[$stamp] ?? [];
    }

    /**
     * @template T of Stamp
     * @param class-string<T> $stamp
     * @return T|null
     */
    public function get(string $stamp): ?Stamp
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

    /**
     * @param class-string<Stamp> $stamp
     */
    public function count(string $stamp): int
    {
        return count($this->stamps[$stamp] ?? []);
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
