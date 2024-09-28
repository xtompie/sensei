<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Timer\Timer;

class ProfileDao
{
    public function __construct(
        protected Data $data,
        protected Timer $timer,
    ) {
    }

    public function start(): void
    {
        $this->timer = Timer::launch();
    }

    /**
     * @param string $query
     * @param array<mixed> $binds
     * @return void
     */
    public function query(string $query, array $binds): void
    {
        $this->data->add('dao.query', [
            'query' => substr($query, 0, 1000),
            'binds' => $binds,
            'duration' => $this->timer->get(),
        ]);
    }

    /**
     * @param string $query
     * @param array<mixed> $binds
     * @param int $affectedRows
     * @return void
     */
    public function command(string $query, array $binds, int $affectedRows): void
    {
        $this->data->add(type: 'shared.dao.command', data: [
            'command' => substr($query, 0, 1000),
            'bindings' => $binds,
            'duration' => $this->timer->get(),
            'affected_rows' => $affectedRows,
        ]);
    }

    /**
     * @param string $command
     * @param array<mixed> $binds
     * @return void
     */
    public function stream(string $command, array $binds): void
    {
        $this->data->add(type: 'shared.dao.stream', data: [
            'stream' => substr($command, 0, 1000),
            'bindings' => $binds,
            'duration' => $this->timer->get(),
        ]);
    }
}
