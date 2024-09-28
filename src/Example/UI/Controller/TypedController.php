<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

class TypedController
{
    #[Path('/example/typed')]
    public function __invoke(TypedQuery $query): Response
    {
        return Response::json(['title' => $query->title()]);
    }
}
