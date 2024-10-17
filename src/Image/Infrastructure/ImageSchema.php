<?php

declare(strict_types=1);

namespace App\Image\Infrastructure;

use App\Shared\Schema\Schema;
use App\Shared\Schema\Column;
use App\Shared\Schema\IntegerType;
use App\Shared\Schema\StringType;
use App\Shared\Schema\Table;
use Generator;

class ImageSchema implements Schema
{
    /**
     * @return Generator<Table>
     */
    public function tables(): Generator
    {
        yield new Table(
            name: 'image',
            columns: [
                new Column(name: 'ai', type: new IntegerType(), primary: true, autoincrement: true),
                new Column(name: 'id', type: new StringType(), unique: true),
                new Column(name: 'media', type: new StringType(), index: true),
                new Column(name: 'source', type: new StringType(), index: true),
            ],
        );
    }
}
