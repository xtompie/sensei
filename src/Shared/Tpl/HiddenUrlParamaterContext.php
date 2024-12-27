<?php

declare(strict_types=1);

namespace App\Shared\Tpl;

use App\Shared\Http\UrlParameterContext;

class HiddenUrlParamaterContext
{
    public function __construct(
        private UrlParameterContext $urlParamContext,
        private HiddenData $hiddenData,
    ) {
    }

    public function __invoke(): string
    {
        return $this->hiddenData->__invoke($this->urlParamContext->context());
    }
}
