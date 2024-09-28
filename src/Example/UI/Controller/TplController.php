<?php

declare(strict_types=1);

namespace App\Example\UI\Controller;

use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

class TplController
{
    #[Path('example/tpl')]
    public function __invoke(): Response
    {
        return Response::tpl('src/Example/UI/Tpl/content.tpl.php', ['title' => 'Foo']);
    }
}
