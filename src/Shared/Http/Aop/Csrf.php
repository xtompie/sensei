<?php

declare(strict_types=1);

namespace App\Shared\Http\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Http\Csrf as HttpCsrf;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Csrf implements Advice
{
    public function __construct(
        private bool $enable,
    ) {
    }

    public function __invoke(Invocation $invocation, HttpCsrf $csrf): mixed
    {
        $csrf->enable($this->enable);

        return $invocation();
    }
}
