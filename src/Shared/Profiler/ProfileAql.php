<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use Xtompie\Aql\Result;

class ProfileAql
{
    public function __construct(
        protected Data $data,
    ) {
    }

    /**
     * @param array<string, mixed> $aql
     * @param Result $result
     * @return void
     */
    public function __invoke(array $aql, Result $result): void
    {
        $this->data->add(type: 'shared.aql', data: [
            'aql' => $aql,
            'sql' => $result->sql(),
            'binds' => $result->binds(),
        ]);
    }
}
