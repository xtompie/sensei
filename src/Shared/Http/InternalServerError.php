<?php

declare(strict_types=1);

namespace App\Shared\Http;

class InternalServerError
{
    public function __invoke(): Response
    {
        return Response::new(
            status: 500,
            body: '500 Internal Server Error',
        );
    }
}
