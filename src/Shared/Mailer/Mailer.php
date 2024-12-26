<?php

declare(strict_types=1);

namespace App\Shared\Mailer;

use App\Shared\Env\Env;
use Symfony\Component\Mailer\Mailer as BaseMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class Mailer
{
    public function __construct(
        protected Env $env,
        protected BaseMailer $mailer,
    ) {
        $this->mailer = new BaseMailer(
            transport: Transport::fromDsn(
                dsn: $this->env->APP_MAILER_DSN()
            )
        );
    }

    public function __invoke(
        string $to,
        string $subject,
        ?string $text = null,
        ?string $html = null,
        ?AttachmentCollection $attachments = null,
    ): void {
        $email = (new Email())
            ->from($this->env->APP_MAILER_FROM())
            ->to($to)
            ->subject($this->env->APP_MAILER_SUBJECT_PREFIX() . $subject)
        ;
        if ($text) {
            $email->text($text);
        }
        if ($html) {
            $email->html($html);
        }
        if ($attachments) {
            foreach ($attachments->all() as $attachment) {
                $email->attach(
                    body: $attachment->body(),
                    name: $attachment->name(),
                    contentType: $attachment->contentType()->value,
                );
            }
        }
        $this->mailer->send(message: $email);
    }
}
