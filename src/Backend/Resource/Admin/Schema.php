<?php

declare(strict_types=1);

namespace App\Backend\Resource\Admin;

use App\Shared\Db\Schema\Column;
use App\Shared\Db\Schema\DateTimeType;
use App\Shared\Db\Schema\Schema as DbSchema;
use App\Shared\Db\Schema\StringType;
use App\Shared\Db\Schema\Table;
use Generator;

class Schema implements DbSchema
{
    public function tables(): Generator
    {
        yield new Table(
            name: __FUNCTION__,
            columns: [
                new Column(name: 'id', type: new StringType(), primary: true),
                new Column(name: 'created_at', type: new DateTimeType(), index: true),
                new Column(name: 'updated_at', type: new DateTimeType(), index: true),
                new Column(name: 'email', type: new StringType(), unique: true),
                new Column(name: 'password', type: new StringType(), nullable: true),
                new Column(name: 'reset_token', type: new StringType(), nullable: true, index: true),
                new Column(name: 'reset_at', type: new DateTimeType(), nullable: true),
                new Column(name: 'role', type: new StringType()),
            ],
        );
    }
}
