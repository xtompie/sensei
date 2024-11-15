<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

class Log
{
    public function __construct(
        protected Data $data,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public function __invoke(array $data): void
    {
        $this->data->add(type: 'shared.profiler.log', data: $data);
    }
}
