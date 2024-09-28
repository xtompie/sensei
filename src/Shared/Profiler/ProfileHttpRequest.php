<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Http\Request;

class ProfileHttpRequest
{
    public function __construct(
        private Data $data,
        private Request $request,
    ) {
    }

    public function __invoke(): void
    {
        $this->data->add(type: 'shared.http.request.uri', data: $this->request->getUri()->__toString());
        $this->data->add(type: 'shared.http.request.method', data: $this->request->getMethod());
        $this->data->add(type: 'shared.http.request.query', data: $this->request->getQueryParams());
        $this->data->add(type: 'shared.http.request.body', data: $this->request->getParsedBody());
    }
}
