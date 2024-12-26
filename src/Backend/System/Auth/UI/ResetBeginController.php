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
use App\Shared\Http\Url;
use App\Shared\I18n\Translator;
use App\Shared\Mailer\Mailer;
use Xtompie\Result\ErrorCollection;

#[Path('/backend/system/auth/reset'), GET, POST]
class ResetBeginController implements Controller
{
    public function __construct(
        private AuthRepository $authRepository,
        private GetLoggedAuth $getLoggedAuth,
        private Mailer $mailer,
        private Request $request,
        private Url $url,
        private Translator $translator,
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

        $this->mail(email: $auth->email(), token: $token);

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

    private function mail(string $email, string $token): void
    {
        $url = $this->url->__invoke(
            controller: ResetFinalizeController::class,
            parameters: ['token' => $token]
        );

        $t = $this->translator;

        $this->mailer->__invoke(
            to: $email,
            subject: $t('backend.Reset password'),
            text: $t('backend.system.auth.reset_begin.body', ['link' => $url]),
        );
    }
}
