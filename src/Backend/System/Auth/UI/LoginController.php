<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

use App\Backend\System\Auth\Application\GetLoggedAuth;
use App\Backend\System\Auth\Application\LoginByPassword;
use App\Backend\System\Auth\UI\LoginBody;
use App\Backend\System\Index\IndexController;
use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;
use Xtompie\Result\ErrorCollection;
use Xtompie\Typed\Typed;

#[Path('/backend/system/auth/login')]
class LoginController implements Controller
{
    public function __construct(
        private GetLoggedAuth $getLoggedAuth,
        private LoginByPassword $loginByPassword,
        private Request $request,
    ) {
    }

    public function __invoke(): Response
    {
        if ($this->getLoggedAuth->__invoke()) {
            return $this->redirect();
        }

        $body = Typed::object(LoginBody::class, $this->request->query());
        if ($body instanceof ErrorCollection) {
            return $this->view($body);
        }

        $ok = $this->loginByPassword->__invoke($body->email(), $body->password());

        if (!$ok) {
            return $this->view(ErrorCollection::ofErrorMsg('Invalid email or password'));
        }

        return $this->redirect();
    }

    private function view(?ErrorCollection $errors): Response
    {
        return Response::tpl('/src/Backend/System/Auth/UI/LoginController.tpl.php', [
            'data' => $this->request->body(),
            'errors' => $errors ?: ErrorCollection::ofEmpty(),
        ]);
    }

    private function redirect(): Response
    {
        $goto = $this->request->query()['goto'] ?? null;

        if ($goto !== null && is_string($goto) && $goto !== '' && $goto[0] === '/') {
            return Response::redirect($goto);
        }

        return Response::redirectToController(IndexController::class);
    }
}
