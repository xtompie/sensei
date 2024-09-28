<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Container\Container;
use App\Shared\Kernel\Debug;
use Generator;
use PDO;
use Xtompie\Dao\PdoAdapter as BasePdoAdapter;

class PdoAdapter extends BasePdoAdapter
{
    public function __construct(
        protected PDO $pdo,
        protected Debug $debug,
    ) {
    }

    /**
     * @param string $query
     * @param array<mixed> $binds
     * @return array<array<string, mixed>>
     */
    public function query(string $query, array $binds): array
    {
        if ($this->debug->__invoke()) {
            $profile = Container::container()->get(ProfileDao::class);
            $profile->start();
            $result = parent::query($query, $binds);
            $profile->query($query, $binds);
            return $result;
        }

        return parent::query($query, $binds);
    }

    /**
     * @param string $query
     * @param array<mixed> $binds
     * @return int
     */
    public function command(string $query, array $binds): int
    {
        if ($this->debug->__invoke()) {
            $profile = Container::container()->get(ProfileDao::class);
            $profile->start();
            $affectedRows = parent::command($query, $binds);
            $profile->command($query, $binds, affectedRows: $affectedRows);
            return $affectedRows;
        }

        return parent::command($query, $binds);
    }

    /**
     * @param string $command
     * @param array<mixed> $binds
     * @return Generator<array<string, mixed>>
     */
    public function stream(string $command, array $binds): Generator
    {
        if ($this->debug->__invoke()) {
            $profile = Container::container()->get(ProfileDao::class);
            $profile->start();
            $stream = parent::stream($command, $binds);
            $profile->stream($command, $binds);
            return $stream;
        }

        return parent::stream($command, $binds);
    }
}
