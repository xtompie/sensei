<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Backend\System\Auth\Application\AuthRepository;
use App\Backend\System\Auth\Application\GetLoggedAuth;
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
class ResetBeginController implements Controller
{
    public function __construct(
        private GetLoggedAuth $getLoggedAuth,
        private AuthRepository $authRepository,
        private Request $request,
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

        $value = $this->request->bodyTypedOrErrors(ResetBeginBody::class);
        if ($value instanceof ErrorCollection) {
            return $this->view();
        }

        $auth = $this->authRepository->findByEmail($value->email());
        if (!$auth) {
            return $this->view(done: true);
        }

        $token = $this->authRepository->beginReset($auth->id());
        if (!$token) {
            return $this->view(done: true);
        }

        return $this->view(done: true);
    }

    private function view(bool $done = false, ?ErrorCollection $errors = null): Response
    {
        return Response::tpl('/src/Backend/System/Auth/UI/ResetBeginController.tpl.php', [
            'done' => $done,
            'value' => $this->request->body(),
            'errors' => UberErrorCollection::of($errors ?: ErrorCollection::ofEmpty()),
        ]);
    }
}
