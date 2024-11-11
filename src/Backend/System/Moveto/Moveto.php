<?php

declare(strict_types=1);

namespace App\Backend\System\Moveto;

use App\Shared\Http\Request;

final class Moveto
{
    public function __construct(
        private Request $request,
    ) {
    }

    public function __invoke(): int
    {
        $body = $this->request->body();
        if (!isset($body['_moveto'])) {
            return 0;
        }
        if (!is_string($body['_moveto'])) {
            return 0;
        }
        return intval($body['_moveto']);
    }
}
