<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Contract\Query;
use Xtompie\Typed\LengthMin;

class TypedQuery implements Query
{
    public function __construct(
        #[LengthMin(3)]
        private string $title,
    ) {
    }

    public function title(): string
    {
        return $this->title;
    }
}
