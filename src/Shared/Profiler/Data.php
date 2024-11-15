<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

class Data
{
    /**
     * @param array<array<string, mixed>> $data
     * @param int $limitPerType
     * @param array<string,int> $counts
     */
    public function __construct(
        protected array $data = [],
        protected int $limitPerType = 100,
        protected array $counts = [],
    ) {
    }

    /**
     * @param string $type
     * @param mixed $data
     */
    public function add(string $type, mixed $data): void
    {
        if (!isset($this->counts[$type])) {
            $this->counts[$type] = 0;
        }

        if ($this->counts[$type] === $this->limitPerType) {
            $this->data[] = ['type' => 'shared.profiler.limit', 'data' => $type];
        }

        if ($this->counts[$type] >= $this->limitPerType) {
            $this->counts[$type]++;
            return;
        }

        $this->counts[$type]++;
        $this->data[] = ['type' => $type, 'data' => $data];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function get(): array
    {
        return $this->data;
    }
}
