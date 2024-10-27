<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Type\EnumIdCases;

/**
 * @extends EnumIdCases<Priority>
 */
final class Priority extends EnumIdCases
{
    protected static array $valid = ['high', 'default', 'low'];

    protected static string $collection = PriorityCollection::class;

    public static function default(): Priority
    {
        return new self('default');
    }

    public static function low(): Priority
    {
        return new self('low');
    }

    public static function high(): Priority
    {
        return new self('high');
    }
}
