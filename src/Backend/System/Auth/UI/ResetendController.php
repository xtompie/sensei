<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Backend\System\Auth\Application\AuthRepository;
use App\Backend\System\Auth\Application\GetLoggedAuth;
use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Flash\Flash;
use App\Backend\System\Index\IndexController;
use App\Backend\System\Validation\UberErrorCollection;
use App\Backend\System\Validation\Validation;
use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\GET;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\POST;
use Xtompie\Result\ErrorCollection;

#[Path('/backend/system/auth/resetend'), GET, POST]
final class ResetendController implements Controller
{
    public function __construct(
        private AuthRepository $authRepository,
        private GetLoggedAuth $getLoggedAuth,
        private Request $request,
        private Flash $flash,
        private Ctrl $ctrl,
    ) {
    }

    public function __invoke(): Response
    {
        if ($this->getLoggedAuth->__invoke()) {
            return Response::redirectToController(IndexController::class);
        }

        $token = $this->request->query()['token'] ?? '';

        if (!is_string($token) || Validation::of($token)->required()->string()->fail()) {
            return $this->ctrl->notFound();
        }

        $auth = $this->authRepository->findByResetToken(token: $token);
        if (!$auth) {
            return $this->ctrl->notFound();
        }

        if (!$this->request->post()) {
            return $this->view();
        }

        $value = $this->request->bodyTypedOrErrors(ResetendBody::class);
        if ($value instanceof ErrorCollection) {
            return $this->view(errors: $value);
        }

        $this->authRepository->updatePasswordByResetToken(token: $token, password: $value->password());

        $this->flash->success('backend.Password has been reset');

        return Response::refresh();
    }

    private function view(?ErrorCollection $errors = null): Response
    {
        return Response::tpl('/src/Backend/System/Auth/UI/ResetendController.tpl.php', [
            'value' => $this->request->body(),
            'errors' => UberErrorCollection::of($errors ?: ErrorCollection::ofEmpty()),
        ]);
    }
}
