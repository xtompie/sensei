<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Pilot;

class UberPilot
{
    public function __construct(
        private ResourcePilotRegistry $resourcePilotRegistry,
    ) {
    }

    /**
     * @param array<string, mixed>|null $entity
     * @return array<int, mixed>
     */
    public function more(string $resource, string $action, ?array $entity = null): array
    {
        return $this->resourcePilotRegistry->__call(name: $resource)->more(action: $action, entity: $entity);
    }

    /**
     * @param array<string, mixed>|null $entity
     * @return array<string, mixed>
     */
    public function link(string $resource, string $action, ?array $entity = null, ?string $title = null): array
    {
        return $this->resourcePilotRegistry->__call(name: $resource)->link(action: $action, entity: $entity, title: $title);
    }

    /**
     * @param array<string, mixed>|null $entity
     */
    public function title(string $resource, string $action, ?array $entity = null): ?string
    {
        return $this->resourcePilotRegistry->__call(name: $resource)->title(action: $action, entity: $entity);
    }

    public function sentry(string $resource, string $action, ?string $id = null, ?string $prop = null): string
    {
        return $this->resourcePilotRegistry->__call(name: $resource)->sentry(action: $action, id: $id, prop: $prop);
    }
}
