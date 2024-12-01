<?php

declare(strict_types=1);

namespace App\Backend\System\I18n;

use App\Shared\I18n\Wording as I18nWording;

class Wording implements I18nWording
{
    public function supports(string $language, string $wording): bool
    {
        if ($language !== 'en') {
            return false;
        }

        if (!str_starts_with($wording, 'backend.')) {
            return false;
        }

        return true;
    }

    public function wordings(string $language): array
    {
        return require __DIR__ . '/' . $language . '.php';
    }
}
