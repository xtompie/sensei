<?php

declare(strict_types=1);

namespace App\Shared\Optimize;

use App\Shared\Console\Command;

class OptimizeCommand
{
    public static function command(): Command
    {
        return new Command(name: 'app:optimize', command: self::class);
    }

    public function __construct(
        private Optimize $optimize
    ) {
    }

    public function __invoke(): void
    {
        $this->optimize->__invoke();
    }
}
