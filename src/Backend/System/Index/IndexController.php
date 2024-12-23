<?php

declare(strict_types=1);

namespace App\Backend\System\Index;

use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('/backend')]
class IndexController implements Controller
{
    public function __invoke(): Response
    {
        return Response::html("TODO: Implement the backend index page");
    }
}
