<?php

declare(strict_types=1);

namespace App\Shared\I18n;

class LanguageContext
{
    public function __construct(
        private string $language = 'en',
    ) {
    }

    public function __invoke(): string
    {
        return $this->language;
    }
}
