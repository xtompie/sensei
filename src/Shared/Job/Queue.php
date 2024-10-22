<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Type\EnumIdCases;

/**
 * @extends EnumIdCases<Queue>
 */
final class Queue extends EnumIdCases
{
    protected static array $valid = ['high', 'default', 'low'];

    protected static string $collection = QueueCollection::class;

    public static function default(): Queue
    {
        return new self('default');
    }

    public static function low(): Queue
    {
        return new self('low');
    }

    public static function high(): Queue
    {
        return new self('high');
    }
}
