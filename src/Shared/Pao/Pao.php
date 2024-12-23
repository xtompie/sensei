<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Xtompie\Dao\Dao;

class Pao
{
    public static function hid(string ...$parts): string
    {
        $hash = sha1(serialize($parts));

        return sprintf(
            '%08s-%04s-%04s-%04s-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12)
        );
    }

    public function __construct(
        protected Dao $dao,
    ) {
    }

    /**
     * @param array<string,mixed> $pql
     */
    public function count(array $pql, ?string $count = null): int
    {
        return $this->dao->count(
            query: array_filter(
                array: $pql,
                callback: fn ($v, $k) => !str_starts_with($k, 'pql:'),
                mode: ARRAY_FILTER_USE_BOTH
            ),
            count: $count
        );
    }

    /**
     * @param array<string,mixed> $pql
     * @param array<Hook> $hooks
     * @return array<string,mixed>|null
     */
    public function find(array $pql, array $hooks = []): ?array
    {
        return $this->findAll($pql, $hooks)[0] ?? null;
    }

    /**
     * @param array<string,mixed> $pql
     * @param array<Hook> $hooks
     * @return array<array<string, mixed>>
     */
    public function findAll(array $pql, array $hooks = []): array
    {
        /** @var array<HookLoadRow> $hookLoadRow */
        $hookLoadRow = array_filter($hooks, fn (Hook $hook) => $hook instanceof HookLoadRow);
        $projections = $this->dao->transaction(fn () => $this->fetch(
            pql: $pql,
            parents: [],
            hooks: $hookLoadRow,
        ));

        /** @var array<HookLoadProjection> $hookLoadProjection */
        $hookLoadProjection = array_filter($hooks, fn (Hook $hook) => $hook instanceof HookLoadProjection);
        if ($hookLoadProjection) {
            foreach ($projections as $index => $projection) {
                foreach ($hookLoadProjection as $hook) {
                    $projection = $hook->loadProjection($projection);
                }
                $projections[$index] = $projection;
            }
        }

        return $projections;
    }

    /**
     * @param array<string,mixed> $future
     * @param callable $presentProvider
     * @param array<Hook> $hooks
     */
    public function save(array $future, callable $presentProvider, array $hooks = []): SaveResult
    {
        return $this->dao->transaction(function () use ($future, $presentProvider, $hooks) {
            $present = $presentProvider();
            return $this->sync(
                presentProjection: $present,
                futureProjection: $future,
                hooks: $hooks,
            );
        });
    }

    public function remove(callable $presentProvider): void
    {
        $this->dao->transaction(function () use ($presentProvider) {
            $present = $presentProvider();
            if ($present === null) {
                return;
            }
            $this->sync(
                presentProjection: $present,
                futureProjection: null,
                hooks: []
            );
        });
    }

    /**
     * @param array<string,mixed> $pql
     * @param array<string> $parents
     * @param array<HookLoadRow> $hooks
     * @return array<array<string, mixed>>
     */
    protected function fetch(array $pql, array $parents, array $hooks): array
    {
        $projections = $this->dao->query($this->query($pql, $parents));
        $projections = array_map(
            function (array $tuple) use ($pql, $hooks) {
                $tuple[':table'] = $pql['from'];
                foreach ($hooks as $hook) {
                    $tuple = $hook->loadRow($tuple);
                }
                return $tuple;
            },
            $projections
        );
        $ids = array_column($projections, 'id');
        if ($projections) {
            foreach ($pql as $key => $value) {
                if (str_starts_with($key, 'pql:children:')) {
                    $field = substr($key, strlen('pql:children:'));
                    /** @var array<string,mixed> $value */
                    $projections = $this->children($projections, $field, $value, $ids, $hooks);
                }
            }
        }
        return $projections;
    }

    /**
     * @param array<array<string, mixed>> $results
     * @param array<string,mixed> $pql
     * @param array<string> $ids
     * @param array<HookLoadRow> $hooks
     * @return array<array<string, mixed>>
     */
    protected function children(array $results, string $field, array $pql, array $ids, array $hooks): array
    {
        $children = $this->fetch($pql, $ids, $hooks);
        $pql_parent = $pql['pql:parent'];

        return array_map(
            fn ($result) =>
                $result + [
                    $field => array_values(array_filter(
                        $children,
                        fn ($child) => $child[$pql_parent] == $result['id']
                    )),
                ],
            $results,
        );
    }

    /**
     * @param array<string,mixed> $pql
     * @param array<string> $parents
     * @return array<string,mixed>
     */
    protected function query(array $pql, array $parents): array
    {
        $aql = $pql;

        if (!isset($aql['select'])) {
            $aql['select'] = '*';
        }

        foreach ($aql as $key => $value) {
            if (str_starts_with($key, 'pql:')) {
                unset($aql[$key]);
            }
        }

        if (isset($pql['pql:parent'])) {
            if (!is_string($pql['pql:parent'])) {
                throw new \InvalidArgumentException('pql:parent must be a string');
            }
            if (isset($aql['where'])) {
                throw new \InvalidArgumentException('where must be an array');
            }
            if (!isset($aql['where']) || !is_array($aql['where'])) {
                $aql['where'] = [];
            }
            $aql['where'][(string) $pql['pql:parent'] . ':in'] = $parents;
        }

        return $aql;
    }

    /**
     * @param array<string,mixed>|null $presentProjection
     * @param array<string,mixed>|null $futureProjection
     * @param array<Hook> $hooks
     */
    protected function sync(?array $presentProjection, ?array $futureProjection, array $hooks): SaveResult
    {
        /** @var array<HookSaveProjection> $hookSaveProjection */
        $hookSaveProjection = $futureProjection !== null ? array_filter($hooks, fn (Hook $hook) => $hook instanceof HookSaveProjection) : [];

        // HookSaveProjection: ->saveProjection()
        if ($hookSaveProjection && $futureProjection !== null) {
            foreach ($hookSaveProjection as $hook) {
                $futureProjection = $hook->saveProjection(present: $presentProjection, future: $futureProjection);
            }
        }

        /** @var array<HookSync> $hookSync */
        $hookSync = $futureProjection ? array_filter($hooks, fn (Hook $hook) => $hook instanceof HookSync) : [];

        // HookSync: ->syncCheck(), ->syncFutureProjection()
        if ($hookSync && $futureProjection && $presentProjection) {
            foreach ($hookSync as $hook) {
                $result = $hook->syncCheck(present: $presentProjection, future: $futureProjection);
                if ($result !== null) {
                    return $result;
                }
                $futureProjection = $hook->syncFutureProjection(present: $presentProjection, future: $futureProjection);
            }
        }

        /** @var array<string,array{id:string,table:string,data:array<string,mixed>}> $presentRecords */
        $presentRecords = $this->records($presentProjection);
        /** @var array<string,array{id:string,table:string,data:array<string,mixed>}> $futureRecords */
        $futureRecords = $this->records($futureProjection);

        // HookSync: ->syncUpdateWhere(), ->syncAffectedRows()
        if ($hookSync && $futureProjection && $presentProjection) {
            array_shift($presentRecords);
            /** @var array{id:string,table:string,data:array<string,mixed>} $futureRoot */
            $futureRoot = array_shift($futureRecords);
            $where = [];
            foreach ($hookSync as $hook) {
                $where = $hook->syncUpdateWhere(present: $presentProjection, future: $futureProjection, where: $where);
            }
            $where['id'] = $futureRoot['id'];
            $affectedRows = $this->dao->update(
                table: $futureRoot['table'],
                set: $futureRoot['data'],
                where: $where,
            );
            foreach ($hookSync as $hook) {
                $result = $hook->syncAffectedRows(affectedRows: $affectedRows);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        $presentIds = array_keys($presentRecords);
        $futureIds = array_keys($futureRecords);
        foreach (array_diff($presentIds, $futureIds) as $uid) {
            $this->dao->delete(
                table: $presentRecords[$uid]['table'],
                where: ['id' => $futureRecords[$uid]['id']],
            );
        }
        foreach (array_diff($futureIds, $presentIds) as $uid) {
            $this->dao->insert(
                table: $futureRecords[$uid]['table'],
                values: $futureRecords[$uid]['data']
            );
        }
        foreach (array_intersect($presentIds, $futureIds) as $uid) {
            $this->dao->update(
                table: $futureRecords[$uid]['table'],
                set: $futureRecords[$uid]['data'],
                where: ['id' => $futureRecords[$uid]['id']],
            );
        }

        return new SaveResultSuccess();
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

        $data = $projection;
        unset($data[':table']);
        $data = array_filter($data, fn ($value) => !is_array($value));

        /** @var string $table */
        $table = $projection[':table'];
        /** @var string $id */
        $id = $projection['id'];

        $records[$table . ':' . $id] = [
            'table' => $table,
            'id' => $id,
            'data' => $data,
        ];

        foreach ($projection as $projection_value) {
            if (is_array($projection_value)) {
                foreach ($projection_value as $child) {
                    /** @var array<string,mixed> $child */
                    $records += $this->records($child);
                }
            }
        }

        return $records;
    }
}
