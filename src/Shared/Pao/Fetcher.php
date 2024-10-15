<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Xtompie\Dao\Dao;

class Fetcher
{
    public function __construct(
        protected Dao $dao,
    ) {
    }

    /**
     * @param array<string, mixed> $pql
     */
    public function count(array $pql, ?string $count = null): int
    {
        return $this->dao->count(
            array_filter(
                $pql,
                fn ($v, $k) => !str_starts_with($k, 'pql:'),
                ARRAY_FILTER_USE_BOTH
            ),
            $count
        );
    }

    /**
     * @param array<string, mixed> $pql
     * @param array<callable> $hooks
     * @return array<string, mixed>|null
     */
    public function find(array $pql, array $hooks = []): ?array
    {
        return $this->findAll($pql, $hooks)[0] ?? null;
    }

    /**
     * @param array<string, mixed> $pql
     * @param array<callable> $hooks
     * @return array<array<string, mixed>>
     */
    public function findAll(array $pql, array $hooks = []): array
    {
        return $this->pql($pql, [], $hooks);
    }

    /**
     * @param array<string, mixed> $pql
     * @param array<string> $parents
     * @param array<callable> $hooks
     * @return array<array<string, mixed>>
     */
    protected function pql(array $pql, array $parents, array $hooks): array
    {
        $projections = $this->dao->query($this->query($pql, $parents));
        $projections = array_map(
            function (array $tuple) use ($pql, $hooks) {
                unset($tuple['ai']);
                $tuple[':table'] = $pql['from'];
                foreach ($hooks as $hook) {
                    $tuple = $hook($tuple);
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
                    /** @var array<string, mixed> $value */
                    $projections = $this->children($projections, $field, $value, $ids, $hooks);
                }
            }
        }

        return $projections;
    }

    /**
     * @param array<array<string, mixed>> $results
     * @param array<string, mixed> $pql
     * @param array<string> $ids
     * @param array<callable> $hooks
     * @return array<array<string, mixed>>
     */
    protected function children(array $results, string $field, array $pql, array $ids, array $hooks): array
    {
        $children = $this->pql($pql, $ids, $hooks);
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
     * @param array<string, mixed> $pql
     * @param array<string> $parents
     * @return array<string, mixed>
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
}
