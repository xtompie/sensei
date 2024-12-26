<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Backend\System\Auth\Application\GetLoggedAuth;
use App\Backend\System\Auth\Application\Logout;
use App\Backend\System\Index\IndexController;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('/backend/system/auth/logout')]
class LogoutController
{
    public function __construct(
        private GetLoggedAuth $getLoggedAuth,
        private Logout $logout,
    ) {
    }

    public function __invoke(): Response
    {
        if (!$this->getLoggedAuth->__invoke()) {
            return $this->redirect();
        }

        $this->logout->__invoke();

        return $this->redirect();
    }

    private function redirect(): Response
    {
        return Response::redirectToController(IndexController::class);
    }
}
