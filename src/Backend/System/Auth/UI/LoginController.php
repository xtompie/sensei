<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Backend\System\Auth\Application\GetLoggedAuth;
use App\Backend\System\Auth\Application\LoginByPassword;
use App\Backend\System\Index\IndexController;
use App\Backend\System\Validation\UberErrorCollection;
use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\GET;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\POST;
use Xtompie\Result\ErrorCollection;

#[Path('/backend/system/auth/login'), GET, POST]
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

        if (!$this->request->post()) {
            return $this->view();
        }

        $value = $this->request->bodyTypedOrErrors(LoginBody::class);
        if ($value instanceof ErrorCollection) {
            return $this->view($value);
        }

        $logged = $this->loginByPassword->__invoke($value->email(), $value->password());

        if (!$logged) {
            return $this->view(ErrorCollection::ofErrorMsg('Invalid email or password'));
        }

        return $this->redirect();
    }

    private function view(?ErrorCollection $errors = null): Response
    {
        return Response::tpl('/src/Backend/System/Auth/UI/LoginController.tpl.php', [
            'value' => $this->request->body(),
            'errors' => UberErrorCollection::of($errors ?: ErrorCollection::ofEmpty()),
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
