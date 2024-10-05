<?php

declare(strict_types=1);

namespace App\Shared\Http\Route;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class PUT implements Method
{
    public function __toString(): string
    {
        return 'PUT';
    }
}
