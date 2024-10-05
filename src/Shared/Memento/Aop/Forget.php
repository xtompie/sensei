<?php

declare(strict_types=1);

namespace App\Shared\Memento\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Memento\Memento;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Forget implements Advice
{
    public function __construct(
        private string $space,
    ) {
    }

    public function __invoke(Invocation $invocation, Memento $memento): mixed
    {
        $memento->clear($this->space);

        return $invocation();
    }
}
