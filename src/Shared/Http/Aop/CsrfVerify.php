<?php

declare(strict_types=1);

namespace App\Shared\Http\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Http\Csrf;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CsrfVerify implements Advice
{
    public function __invoke(Invocation $invocation, Csrf $csrf, Request $request): mixed
    {
        if ($request->post() && $csrf->enabled() && !$csrf->verify()) {
            return Response::forbidden();
        }

        return $invocation();
    }
}
