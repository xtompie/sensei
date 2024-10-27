<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Type\EnumId;
use App\Shared\Type\EnumIdCases;

/**
 * @extends EnumId<Instruction>
 */
final class Instruction extends EnumId
{
    protected static array $valid = ['sync', 'async', 'done', 'fail', 'archive'];

    public static function async(): Instruction
    {
        return new static(__FUNCTION__);
    }

    public static function sync(): Instruction
    {
        return new static(__FUNCTION__);
    }

    public static function done(): Instruction
    {
        return new static(__FUNCTION__);
    }

    public static function fail(): Instruction
    {
        return new static(__FUNCTION__);
    }

    public static function archive(): Instruction
    {
        return new static(__FUNCTION__);
    }


    public function break(): bool
    {
        return $this->equals(static::async());
    }
}
