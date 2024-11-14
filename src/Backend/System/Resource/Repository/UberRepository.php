<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

use App\Backend\System\Resource\Pilot\ResourcePilotRegistry;
use App\Sentry\Application\Service\Sentry\Sentry;
use Exception;

final class UberRepository
{
    public function __construct(
        private ResourceRepositoryRegistry $resourceRepositoryRegistry,
        private ResourcePilotRegistry $resourcePilotRegistry,
        private Sentry $sentry,
    ) {
    }

    /**
     * @param array<string, mixed>|null $where
     */
    public function count(string $resource, ?array $where): int
    {
        return $this->resourceRepositoryRegistry->__call($resource)->count($where);
    }

    /**
     * @param array<string, mixed>|null $where
     * @return array<int, array<string, mixed>>
     */
    public function findAll(string $resource, ?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        $entities = $this->resourceRepositoryRegistry->__call($resource)->findAll($where, $order, $limit, $offset);
        foreach ($entities as $index => $entity) {
            $id = $entity['id'] ?? null;
            if (!is_string($id) || !$this->sentry->__invoke($this->resourcePilotRegistry->__call($resource)->sentry(action: 'list', id: $id))) {
                unset($entities[$index]);
            }
        }
        return $entities;
    }

    /**
     * @param array<string, mixed>|null $where
     * @return array<string, array<string, mixed>>
     */
    public function findAllKeyed(string $resource, ?array $where = null, ?string $order = null, ?int $limit = null, ?int $offset = null): array
    {
        $entities = [];
        foreach ($this->findAll($resource, $where, $order, $limit, $offset) as $entity) {
            $id = $entity['id'] ?? null;
            if (!is_string($id)) {
                throw new Exception('Entity id is not a string.');
            }
            $entities[$id] = $entity;
        }
        return $entities;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(string $resource, string $id): ?array
    {
        $entity = $this->resourceRepositoryRegistry->__call($resource)->findById($id);
        if (!$entity) {
            return null;
        }
        if (!$this->sentry->__invoke($this->resourcePilotRegistry->__call($resource)->sentry(action: 'detail', id: $id))) {
            return null;
        }
        return $entity;
    }
}
