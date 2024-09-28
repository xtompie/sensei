<?php

declare(strict_types=1);

namespace App\Registry;

use App\Shared\Db\Schema\Table\Table;
use Generator;

class DbSchema
{
    /**
     * @return Generator<Table>
     */
    public static function tables(): Generator
    {
        yield from [];
    }
}
