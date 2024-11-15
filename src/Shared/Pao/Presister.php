<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Xtompie\Dao\Dao;

class Presister
{
    public function __construct(
        protected Dao $dao,
    ) {
    }

    /**
     * @param array<string,mixed> $future
     * @param callable|null $presentProvider
     * @param array<callable> $hooks
     */
    public function save(array $future, ?callable $presentProvider, array $hooks = []): void
    {
        $this->dao->transaction(function () use ($future, $presentProvider, $hooks) {
            $present = $presentProvider ? $presentProvider() : null;
            $future = $this->hooks($present, $future, $hooks);
            $this->presist($present, $future);
        });
    }

    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @param array<callable> $hooks
     * @return array<string,mixed>
     */
    protected function hooks(?array $present, array $future, array $hooks): array
    {
        foreach ($hooks as $hook) {
            $future = $hook($present, $future);
        }
        return $future;
    }

    public function remove(callable $presentProvider): void
    {
        $this->dao->transaction(function () use ($presentProvider) {
            $present = $presentProvider();
            if ($present === null) {
                return;
            }
            $this->presist($present, null);
        });
    }

    /**
     * @param array<string,mixed>|null $presentProjection
     * @param array<string,mixed>|null $futureProjection
     */
    protected function presist(?array $presentProjection, ?array $futureProjection): void
    {
        $presentRecords = $this->records($presentProjection);
        $futureRecords = $this->records($futureProjection);

        $deletes = $this->deletes($presentRecords, $futureRecords);
        $inserts = $this->inserts($presentRecords, $futureRecords);
        $updates = $this->updates($presentRecords, $futureRecords);

        foreach ($deletes as $record) {
            $this->dao->delete($record['table'], ['id' => $record['id']]);
        }
        foreach ($inserts as $record) {
            $this->dao->insert($record['table'], $record['data']);
        }
        foreach ($updates as $record) {
            $this->dao->update($record['table'], $record['data'], ['id' => $record['id']]);
        }
    }

    /**
     * @param array<string,mixed>|null $projection
     * @return array<string,array<string, mixed>>
     */
    protected function records(?array $projection): array
    {
        if ($projection === null) {
            return [];
        }

        $records = $this->record($projection);

        foreach ($projection as $projection_value) {
            if (is_array($projection_value)) {
                foreach ($projection_value as $child) {
                    $records += $this->records($child);
                }
            }
        }

        return $records;
    }

    /**
     * @param array<string,mixed> $projection
     * @return array<string,array<string, mixed>>
     */
    protected function record(array $projection): array
    {
        $table = $projection[':table'];
        $id = $projection['id'];
        unset($projection[':table'], $projection['id']);
        foreach ($projection as $key => $value) {
            if (is_array($value)) {
                unset($projection[$key]);
            }
        }
        $data = $projection;

        return [
            $table . ':' . $id => [
                'table' => $table,
                'id' => $id,
                'data' => $data,
            ],
        ];
    }

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @return array<string,array{table:string,data:array<string,mixed>}>
     */
    protected function inserts(array $present, array $future): array
    {
        $result = [];
        foreach (array_diff(array_keys($future), array_keys($present)) as $id) {
            $result[$id] = [
                'table' => $future[$id]['table'], // @phpstan-ignore-line
                'data' => ['id' => $future[$id]['id']] + $future[$id]['data'], // @phpstan-ignore-line
            ];
        }
        return $result; // @phpstan-ignore-line
    }

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @return array<string,array{table:string,id:string,data:array<string,mixed>}>
     */
    protected function updates(array $present, array $future): array
    {
        $result = [];
        foreach (array_intersect(array_keys($present), array_keys($future)) as $id) {
            if (sha1(serialize($present[$id]['data'])) === sha1(serialize($future[$id]['data']))) { // @phpstan-ignore-line
                continue;
            }
            $result[$id] = [
                'table' => $future[$id]['table'], // @phpstan-ignore-line
                'id' => $future[$id]['id'], // @phpstan-ignore-line
                'data' => $future[$id]['data'], // @phpstan-ignore-line
            ];
        }
        return $result; // @phpstan-ignore-line
    }

    /**
     * @param array<string,mixed> $present
     * @param array<string,mixed> $future
     * @return array<string,array{table:string,id:string}>
     */
    protected function deletes(array $present, array $future): array
    {
        $result = [];
        foreach (array_diff(array_keys($present), array_keys($future)) as $id) {
            $result[$id] = [
                'table' => $present[$id]['table'], // @phpstan-ignore-line
                'id' => $present[$id]['id'], // @phpstan-ignore-line
            ];
        }
        return $result; // @phpstan-ignore-line
    }
}
