<?php

declare(strict_types=1);

namespace App\Shared\Job\Depot\FileDepot;

use App\Shared\Job\Stamp\Stamp;
use stdClass;

class FileDepotPathStamp implements Stamp
{
    public static function fromPrimitive(stdClass $primitive): static
    {
        return new static($primitive->path);
    }

    public function __construct(
        private string $path,
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'path' => $this->path,
        ];
    }
}
