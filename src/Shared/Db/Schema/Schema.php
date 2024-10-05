<?php

declare(strict_types=1);

namespace App\Shared\Db\Schema;

use Generator;

interface Schema
{
    /**
     * @return Generator<Table>
     */
    public function tables(): Generator;
}
