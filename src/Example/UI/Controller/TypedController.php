<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('/example/typed')]
class TypedController implements Controller
{
    public function __invoke(TypedQuery $query): Response
    {
        return Response::json(['title' => $query->title()]);
    }
}
