<?php

declare(strict_types=1);

namespace App\Media\Application\Event;

final class ImageVariantCreated
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
