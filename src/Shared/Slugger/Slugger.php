<?php

declare(strict_types=1);

namespace App\Shared\Slugger;

use Symfony\Component\String\Slugger\AsciiSlugger;

class Slugger
{
    public function __construct(
        protected AsciiSlugger $asciiSlugger,
    ) {
    }

    public function __invoke(string $string): string
    {
        return $this->asciiSlugger->slug($string, '-', 'de')->__toString();
    }
}
