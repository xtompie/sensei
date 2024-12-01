<?php

declare(strict_types=1);

namespace App\Shared\I18n;

final class Translator
{
    /**
     * @param array<string,array<string,string>> $wordings
     */
    public function __construct(
        private LanguageContext $languageContext,
        private WordingDiscoverer $wordingDiscoverer,
        private array $wordings = [],
    ) {
    }

    /**
     * @param array<string,string> $replacements
     */
    public function __invoke(string $key, array $replacements = [], ?string $language = null): string
    {
        $language = $language ?? $this->languageContext->__invoke();

        if (!isset($this->wordings[$language])) {
            $this->wordings[$language] = [];
        }

        if (!isset($this->wordings[$language][$key])) {
            $this->load(language: $language, key: $key);
        }

        if (!isset($this->wordings[$language][$key])) {
            $this->wordings[$language][$key] = $this->default($key);
        }

        $text = $this->wordings[$language][$key];

        if ($replacements) {
            $text = str_replace(array_keys($replacements), array_values($replacements), $text);
        }

        return $text;
    }

    private function default(string $key): string
    {
        return '###' . $key;
    }

    private function load(string $language, string $key): void
    {
        foreach ($this->wordingDiscoverer->instances() as $wording) {
            if (!$wording->supports($language, $key)) {
                continue;
            }
            $this->wordings[$language] += $wording->wordings($language);
        }
    }
}
