<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use App\Shared\Job\Instruction;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use stdClass;

final class InstructionStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        $at = DateTime::createFromFormat(DateTime::ATOM, $primitive->at, new DateTimeZone('UTC'));
        if ($at === false) {
            throw new InvalidArgumentException('Invalid at format');
        }
        return new static(
            at: $at,
            instruction: new Instruction($primitive->instruction),
        );
    }

    public static function sync(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::sync(),
        );
    }

    public static function async(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::async(),
        );
    }

    public static function done(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::done(),
        );
    }

    public static function fail(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::fail(),
        );
    }

    public static function archive(): static
    {
        return new static(
            at: new DateTime('now', new DateTimeZone('UTC')),
            instruction: Instruction::archive(),
        );
    }

    public function __construct(
        private DateTime $at,
        private Instruction $instruction,
    ) {
    }

    public function at(): DateTime
    {
        return $this->at;
    }

    public function instruction(): Instruction
    {
        return $this->instruction;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'at' => $this->at->format(DateTime::ATOM),
            'instruction' => $this->instruction->value(),
        ];
    }
}
