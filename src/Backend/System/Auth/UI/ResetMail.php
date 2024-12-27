<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use App\Shared\Http\Url;
use App\Shared\Http\UrlReference;
use App\Shared\I18n\Translator;
use App\Shared\Mailer\Mailer;

final class ResetMail
{
    public function __construct(
        private Mailer $mailer,
        private Url $url,
        private Translator $translator,
    ) {
    }

    public function __invoke(string $email, string $token): void
    {
        $this->mailer->__invoke(
            to: $email,
            subject: $this->t('backend.Reset password'),
            text: $this->t('backend.system.auth.reset.body', [
                '{link}' => $this->url($token),
            ]),
        );
    }

    /**
     * @param array<string,string> $parameters
     */
    private function t(string $key, array $parameters = []): string
    {
        return $this->translator->__invoke($key, $parameters);
    }

    private function url(string $token): string
    {
        return $this->url->__invoke(
            controller: ResetendController::class,
            parameters: ['token' => $token],
            reference: UrlReference::ABSOLUTE_URL,
        );
    }
}
