<?php

declare(strict_types=1);

namespace App\Shared\Db\Schema;

class TextType extends Type
{
    public function __construct(
    ) {
        parent::__construct(type: 'text');
    }
}
