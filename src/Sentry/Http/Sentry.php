<?php

declare(strict_types=1);

namespace App\Sentry\Http;

use App\Sentry\System\Rid;
use App\Sentry\System\Sentry as SystemSentry;
use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Http\Response;
use Attribute;
use ReflectionClass;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Sentry implements Advice
{
    /**
     * @param class-string<Rid> $rid
     * @param array<string,mixed> $args
     */
    public function __construct(
        private string $rid,
        private array $args = [],
    ) {
    }

    public function __invoke(Invocation $invocation, SystemSentry $sentry): mixed
    {
        if (!$sentry->__invoke($this->rid())) {
            return Response::forbidden();
        }

        return $invocation();
    }

    private function rid(): Rid
    {
        return (new ReflectionClass($this->rid))->newInstanceArgs($this->args);
    }
}
