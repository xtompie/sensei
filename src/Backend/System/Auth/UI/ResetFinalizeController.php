<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\GET;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\POST;

#[Path('/backend/system/auth/resetend'), GET, POST]
class ResetFinalizeController implements Controller
{
    public function __construct(
    ) {
    }

    public function __invoke(): Response
    {
        return Response::html('ResetFinalizeController');
    }
}
