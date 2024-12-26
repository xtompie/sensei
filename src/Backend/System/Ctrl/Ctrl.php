<?php

declare(strict_types=1);

namespace App\Backend\System\Ctrl;

use App\Backend\System\Auth\Application\Auth;
use App\Backend\System\Auth\Application\GetLoggedAuth;
use App\Backend\System\Auth\UI\LoginController;
use App\Backend\System\Flash\Flash;
use App\Backend\System\Index\IndexController;
use App\Backend\System\Resource\Selection\Selection;
use App\Backend\System\Validation\Validation;
use App\Sentry\System\Rid;
use App\Sentry\System\Sentry;
use App\Shared\Http\Csrf;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Url;
use App\Shared\Tpl\Tpl;

class Ctrl
{
    public function __construct(
        protected Csrf $csrf,
        protected Flash $flash,
        protected GetLoggedAuth $getLoggedAuth,
        protected Request $request,
        protected Selection $selection,
        protected Sentry $sentry,
        protected Tpl $tpl,
        protected Url $url,
        protected Validation $validation,
    ) {
    }

    public function init(
        ?Rid $sentry = null,
        bool $logged = true,
        bool $csrf = true,
        bool $selection = true,
    ): ?Response {
        if ($selection) {
            $this->selection->init();
        }

        if ($csrf && $this->request->post() && !$this->csrf->verify()) {
            return $this->forbidden();
        }

        if ($logged && !$this->getLoggedAuth->__invoke() instanceof Auth) {
            return Response::redirectToController(LoginController::class, ['goto' => $this->request->getPathAndQuery()]);
        }

        if (!$logged && $this->getLoggedAuth->__invoke() instanceof Auth) {
            return Response::redirectToController(IndexController::class);
        }

        if ($sentry !== null && !$this->sentry->__invoke($sentry)) {
            return $this->forbidden();
        }

        return null;
    }

    public function sentry(Rid $rid): bool
    {
        return $this->sentry->__invoke($rid);
    }

    /**
     * @return array<string,mixed>|null
     */
    public function body(): ?array
    {
        return $this->request->body();
    }

    public function submit(): ?string
    {
        $body = $this->body();
        $submits = $body['_submit'] ?? null;

        if ($submits === null || !is_array($submits)) {
            return null;
        }

        $submit = array_key_first($submits);
        $submit = is_string($submit) ? $submit : null;

        return $submit;
    }

    public function forbidden(): Response
    {
        return Response::html(body: $this->tpl->__invoke('/src/Backend/System/Error/Error403.tpl.php'), status: 403);
    }

    public function unauthorized(): Response
    {
        return Response::html(body: $this->tpl->__invoke('/src/Backend/System/Error/Error401.tpl.php'), status: 401);
    }

    public function notFound(): Response
    {
        return Response::html(body: $this->tpl->__invoke('/src/Backend/System/Error/Error404.tpl.php'), status: 404);
    }

    public function flash(string $msg, string $type = 'info', string $format = 'text'): void
    {
        $this->flash->add(msg: $msg, type: $type, format: $format);
    }

    /**
     * @return array<string,mixed>
     */
    public function query(): array
    {
        return $this->request->query();
    }

    public function selection(): Selection
    {
        return $this->selection;
    }

    public function validation(mixed $value): Validation
    {
        return $this->validation->withSubject($value);
    }

    public function url(): Url
    {
        return $this->url;
    }
}
