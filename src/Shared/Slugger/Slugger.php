<?php

declare(strict_types=1);

namespace App\Shared\Slugger;

use App\Shared\I18n\LanguageContext;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Slugger
{
    public function __construct(
        protected AsciiSlugger $asciiSlugger,
        protected LanguageContext $languageContext
    ) {
    }

    public function __invoke(string $string): string
    {
        return $this->asciiSlugger
            ->slug(
                string: $string,
                separator: '-',
                locale: $this->languageContext->__invoke()
            )
            ->__toString()
        ;
    }
}
