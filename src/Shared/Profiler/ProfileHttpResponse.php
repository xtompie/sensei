<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Http\Response;

class ProfileHttpResponse
{
    public function __construct(
        private Data $data,
    ) {
    }

    public function __invoke(Response $response): void
    {
        $this->data->add(type: 'shared.http.response.status', data: $response->getStatusCode());
        $this->data->add(type: 'shared.http.response.headers', data: $response->getHeaders());
    }
}
