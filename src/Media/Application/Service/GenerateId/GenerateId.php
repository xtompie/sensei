<?php

declare(strict_types=1);

namespace App\Media\Application\Service\GenerateId;

use App\Shared\Gen\Gen;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GenerateId
{
    public function __construct(
        protected AsciiSlugger $asciiSlugger,
    ) {
    }

    public function __invoke(string $type, string $space, string $name): string
    {
        $uuid = Gen::uuid4();
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $name = substr($name, 0, -(strlen($ext) + 1));

        return
            $type
            . '/' . $this->slug($space)
            . '/' . $uuid[0] . $uuid[1]
            . '/' . $uuid[2] . $uuid[3]
            . '/' . $uuid
            . '.' . $this->slug($name)
            . '.' . $this->slug($ext)
        ;
    }

    protected function slug(string $string): string
    {
        return $this->asciiSlugger->slug($string, '-', 'de')->__toString();
    }
}
