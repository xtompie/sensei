<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('example/tpl')]
class TplController implements Controller
{
    public function __invoke(): Response
    {
        return Response::tpl('src/Example/UI/Tpl/content.tpl.php', ['title' => 'Foo']);
    }
}
