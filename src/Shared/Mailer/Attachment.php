<?php

declare(strict_types=1);

namespace App\Shared\Mailer;

class Attachment
{
    public function __construct(
        protected string $body,
        protected string $name,
        protected ContentType $contentType,
    ) {
    }

    public function body(): string
    {
        return $this->body;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function contentType(): ContentType
    {
        return $this->contentType;
    }
}
