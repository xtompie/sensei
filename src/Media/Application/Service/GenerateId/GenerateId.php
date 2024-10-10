<?php

declare(strict_types=1);

namespace App\Media\Application\Service\GenerateId;

use App\Media\Application\Model\ImageSpace;
use App\Media\Application\Model\MediaType;
use App\Shared\Gen\Gen;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class GenerateId
{
    public function __construct(
        private AsciiSlugger $asciiSlugger,
    ) {
    }

    public function __invoke(MediaType $type, ImageSpace $space, string $name, string $extension): string
    {
        $uuid = Gen::uuid4();

        return
            $type
            . '/' . $space->value()
            . '/' . $uuid[0] . $uuid[1]
            . '/' . $uuid[2] . $uuid[3]
            . '/' . $uuid
            . '.' . $this->slug($name)
            . '.' . $extension
        ;
    }

    private function slug(string $string): string
    {
        return $this->asciiSlugger->slug($string, '-', 'de')->__toString();
    }
}
