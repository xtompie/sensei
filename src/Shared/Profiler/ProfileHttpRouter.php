<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Http\Response;
use App\Shared\Http\RouterResult;

class ProfileHttpRouter
{
    public function __construct(
        private Data $data,
    ) {
    }

    public function __invoke(mixed $result): void
    {
        if ($result instanceof Response) {
            $this->data->add(type: 'shared.http.router.response.status', data: $result->getStatusCode());
        }

        if ($result instanceof RouterResult) {
            $this->data->add(type: 'shared.http.router.controller', data: $result->controller);
            $this->data->add(type: 'shared.http.router.parameters', data: $result->parameters);
        }
    }
}
