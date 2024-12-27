<?php

declare(strict_types=1);

namespace App\Backend\System\Index;

use App\Backend\System\Ctrl\Ctrl;
use App\Sentry\Rid\BackendRid;
use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('/backend')]
class IndexController implements Controller
{
    public function __construct(
        private Ctrl $ctrl,
    ) {
    }

    public function __invoke(): Response
    {
        $init = $this->ctrl->init(sentry: new BackendRid(), selection: false);
        if ($init !== null) {
            return $init;
        }

        return Response::tpl('/src/Backend/System/Index/IndexController.tpl.php');
    }
}
