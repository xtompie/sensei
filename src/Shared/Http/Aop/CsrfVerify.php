<?php

declare(strict_types=1);

namespace App\Shared\Http\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Http\Csrf;
use App\Shared\Http\CsrfEnabled;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CsrfVerify implements Advice
{
    public function __invoke(Invocation $invocation, Csrf $csrf, CsrfEnabled $csrfEnabled, Request $request): mixed
    {
        if ($request->post() && $csrfEnabled->enabled() && !$csrf->verify()) {
            return Response::forbidden();
        }

        return $invocation();
    }
}
