<?php

declare(strict_types=1);

namespace App\Media\Application\Event;

final class ImageCreated
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
