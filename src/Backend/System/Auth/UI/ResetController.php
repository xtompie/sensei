<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Backend\System\Auth\Application\AuthRepository;
use App\Backend\System\Auth\Application\GetLoggedAuth;
use App\Backend\System\Flash\Flash;
use App\Backend\System\Index\IndexController;
use App\Backend\System\Validation\UberErrorCollection;
use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\GET;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\POST;
use Xtompie\Result\ErrorCollection;

#[Path('/backend/system/auth/reset'), GET, POST]
class ResetController implements Controller
{
    public function __construct(
        private AuthRepository $authRepository,
        private GetLoggedAuth $getLoggedAuth,
        private Request $request,
        private Flash $flash,
        private ResetMail $resetMail,
    ) {
    }

    public function __invoke(): Response
    {
        if ($this->getLoggedAuth->__invoke()) {
            return Response::redirectToController(IndexController::class);
        }

        if (!$this->request->post()) {
            return $this->view();
        }

        $value = $this->request->bodyTypedOrErrors(ResetBody::class);
        if ($value instanceof ErrorCollection) {
            return $this->view(errors: $value);
        }

        $auth = $this->authRepository->findByEmail($value->email());
        if (!$auth) {
            return $this->done();
        }

        $token = $this->authRepository->reset($auth->id());
        if (!is_string($token)) {
            return $this->done();
        }

        $this->resetMail->__invoke(email: $auth->email(), token: $token);

        return $this->done();
    }

    private function done(): Response
    {
        $this->flash->success('backend.Reset password email sent');
        return Response::refresh();
    }

    private function view(?ErrorCollection $errors = null): Response
    {
        return Response::tpl('/src/Backend/System/Auth/UI/ResetController.tpl.php', [
            'value' => $this->request->body(),
            'errors' => UberErrorCollection::of($errors ?: ErrorCollection::ofEmpty()),
        ]);
    }
}
