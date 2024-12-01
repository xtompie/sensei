<?php

declare(strict_types=1);

namespace App\Shared\I18n;

interface Wording
{
    public function supports(string $language, string $wording): bool;

    /**
     * @return array<string,string>
     */
    public function wordings(string $language): array;
}
