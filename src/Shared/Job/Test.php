<?php

declare(strict_types=1);

namespace App\Shared\Job;

class Test
{
    public function __construct(
        private string $text,
    ) {
    }

    public function __invoke(): void
    {
        throw new \Exception('Test exception');
        echo $this->text;
    }
}
