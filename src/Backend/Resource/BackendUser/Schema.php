<?php

declare(strict_types=1);

namespace App\Backend\Resource\BackendUser;

use App\Shared\Schema\Column;
use App\Shared\Schema\DateTimeType;
use App\Shared\Schema\Schema as DbSchema;
use App\Shared\Schema\StringType;
use App\Shared\Schema\Table;
use Generator;

class Schema implements DbSchema
{
    public function tables(): Generator
    {
        yield new Table(
            name: 'backend_user',
            columns: [
                new Column(name: 'id', type: new StringType(), primary: true),
                new Column(name: 'tenant', type: new StringType(), index: true),
                new Column(name: 'created_at', type: new DateTimeType(), index: true),
                new Column(name: 'updated_at', type: new DateTimeType(), index: true),
                new Column(name: 'email', type: new StringType(), index: true),
                new Column(name: 'password', type: new StringType(), nullable: true),
                new Column(name: 'reset_token', type: new StringType(), nullable: true, index: true),
                new Column(name: 'reset_at', type: new DateTimeType(), nullable: true),
                new Column(name: 'role', type: new StringType()),
            ],
        );
    }
}
