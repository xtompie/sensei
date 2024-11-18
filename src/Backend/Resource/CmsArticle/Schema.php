<?php

declare(strict_types=1);

namespace App\Backend\Resource\CmsArticle;

use App\Shared\Schema\Column;
use App\Shared\Schema\DateTimeType;
use App\Shared\Schema\Schema as DbSchema;
use App\Shared\Schema\StringType;
use App\Shared\Schema\Table;
use App\Shared\Schema\TextType;
use Generator;

class Schema implements DbSchema
{
    public function tables(): Generator
    {
        yield new Table(
            name: 'cms_article',
            columns: [
                new Column(name: 'id', type: new StringType(), primary: true),
                new Column(name: 'created_at', type: new DateTimeType(), index: true),
                new Column(name: 'updated_at', type: new DateTimeType(), index: true),
                new Column(name: 'title', type: new StringType()),
                new Column(name: 'body', type: new TextType()),
            ],
        );
    }
}
