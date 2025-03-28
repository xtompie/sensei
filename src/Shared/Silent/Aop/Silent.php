<?php

declare(strict_types=1);

namespace App\Shared\Silent\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Silent\Silent as SilentService;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Silent implements Advice
{
    public function __construct(
        private bool $log = true,
    ) {
    }

    public function __invoke(Invocation $invocation, SilentService $silent): mixed
    {
        return $silent($invocation, $this->log);
    }
}
