<?php

declare(strict_types=1);

namespace App\Shared\Db\Schema\Type;

class TextType extends Type
{
    public function __construct(
    ) {
        parent::__construct(type: 'text');
    }
}
