<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use App\Shared\Job\Instruction;
use App\Shared\Timer\Timer;
use DateTime;
use DateTimeZone;
use stdClass;

final class EvaluationStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static(
            at: $primitive->at,
            instruction: new Instruction($primitive->instruction),
            success: $primitive->success,
            error: $primitive->error ?? null,
        );
    }

    public static function sync(Timer $timer, bool $success, ?string $error): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::sync(),
            success: $success,
            duration: $timer->get(),
            error: $error,
        );
    }

    public static function async(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::async(),
            success: true,
        );
    }

    public static function done(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::done(),
            success: true,
        );
    }

    public static function fail(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::fail(),
            success: false,
        );
    }

    public static function archive(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::archive(),
            success: true,
        );
    }

    public function __construct(
        private DateTime $at,
        private Instruction $instruction,
        private bool $success,
        private ?string $error = null,
        private ?float $duration = null,
    ) {
    }

    public function at(): DateTime
    {
        return $this->at;
    }

    public function success(): bool
    {
        return $this->success;
    }

    public function instruction(): Instruction
    {
        return $this->instruction;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function duration(): ?float
    {
        return $this->duration;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'at' => $this->at->format(DateTime::ATOM),
            'instruction' => $this->instruction->value(),
            'success' => $this->success,
            'error' => $this->error,
            'duration' => $this->duration,
        ];
    }
}
