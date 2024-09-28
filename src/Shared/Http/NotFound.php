<?php

declare(strict_types=1);

namespace App\Shared\Http;

class NotFound
{
    public function __invoke(): Response
    {
        return Response::new(
            status: 404,
            body: '404 Not Found',
        );
    }
}
