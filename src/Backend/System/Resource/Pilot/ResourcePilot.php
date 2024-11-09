<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Pilot;

use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Resource\Repository\ResourceRepository;
use App\Backend\System\Resource\Repository\ResourceRepositoryRegistry;
use App\Backend\System\Validation\Validation;
use App\Shared\Container\Container;

abstract class ResourcePilot
{
   /**
     * Name of the resource. This is used to identify the resource in the backend.
     * Same as folder name in src/Backend/Resource. E.g. 'article'.
     */
    public static function resource(): string
    {
        return strtolower(array_slice(explode('\\', static::class), -2, 1)[0]);
    }

    protected function ctrl(): Ctrl
    {
        return Container::container()->get(Ctrl::class);
    }

    protected function repository(): ResourceRepository
    {
        return Container::container()->get(ResourceRepositoryRegistry::class)->__call(static::resource());
    }

    /**
     * Returns array of links.
     *
     * @param array<string, mixed>|null $entity
     * @return array<int, mixed>
     */
    public function breadcrumb(string $action, ?array $entity = null): array
    {
        return match ($action) {
            'index' => [
                $this->link('index', $entity),
            ],
            'create' => [
                $this->link('index', $entity),
                $this->link('create', $entity),
            ],
            'detail' => [
                $this->link('index', $entity),
                $this->link('detail', $entity),
            ],
            'update', 'delete' => [
                $this->link('index', $entity),
                $this->link('detail', $entity),
            ],
            default => [
                $this->link('index', $entity),
                $this->link('detail', $entity),
            ],
        };
    }

    /**
     * Link an array<string, string> with keys: resource, action, sentry, title, url.
     *
     * @param array<string, mixed>|null $entity
     * @return array<string, mixed>
     */
    public function link(string $action, ?array $entity = null, ?string $title = null): array
    {
        return [
            'resource' => static::resource(),
            'action' => $action,
            'sentry' => 'backend.resource.' . static::resource() . '.action.' . $action . ($entity ? '.id.' . $entity['id'] : ''),
            'title' => $title ?: $this->title(action: $action, entity: $entity),
            'url' => $this->url(action: $action, entity: $entity),
        ];
    }

    /**
     * Returns array of links.
     *
     * @param array<string, mixed>|null $entity
     * @return array<int, mixed>
     */
    public function more(string $action, ?array $entity = null): array
    {
        return match ($action) {
            'index' => [
                $this->link('create', $entity),
                $this->link('create', $entity),
                $this->link('create', $entity),
            ],
            'list' => [
                $this->link('detail', $entity, 'Detail'),
                $this->link('update', $entity),
                $this->link('delete', $entity),
            ],
            'detail' => [
                $this->link('update', $entity),
                $this->link('delete', $entity),
            ],
            default => [],
        };
    }

    /**
     * Same as static::resource() but not static.
     */
    public function name(): string
    {
        return static::resource();
    }

    /**
     * Whitle list of entity keys in selection.
     * When using selection mechanism, only these keys will be returned.
     *
     * @return array<string>
     */
    public function selection(): array
    {
        return ['id'];
    }

    /**
     * Title of the entity.
     * @param array<string, mixed>|null $entity
     */
    public function title(string $action, ?array $entity = null): ?string
    {
        return match ($action) {
            'index' => $this->titlePlural(),
            'list' => $this->titleSingular(),
            'create' => 'Create',
            'detail' => $entity ? $this->titleDetail($entity) : 'Detail',
            'update' => 'Update',
            'delete' => 'Delete',
            default => null,
        };
    }

    /**
     * @param array<string, mixed> $entity
     */
    protected function titleDetail(array $entity): string
    {
        $title = isset($entity['title']) && is_string($entity['title']) ? $entity['title'] : null;
        if ($title !== null) {
            return $title;
        }

        if (!isset($entity['id']) || !is_string($entity['id'])) {
            throw new \Exception('Entity has no id');
        }

        return $entity['id'];
    }

    protected function titlePlural(): string
    {
        return ucfirst($this->titleSingular() . 's');
    }

    protected function titleSingular(): string
    {
        return ucfirst($this->name());
    }

    /**
     * Url
     *
     * @param array<string, mixed>|null $entity
     * @param array<string, mixed> $params
     */
    public function url(string $action, ?array $entity = null, array $params = []): string
    {
        /** @var class-string $class */
        $class = 'App\Backend\\Resource\\' . ucfirst(static::resource()) . '\\' . ucfirst($action) . 'Controller';

        return $this->ctrl()->url()->__invoke(
            controller: $class,
            parameters: array_filter([
                'id' => $entity['id'] ?? null,
                ...$params,
            ])
        );
    }

    /**
     * White list of entity keys in write operations.
     * WHen using insert or update, only these keys from $_POST will be used.
     *
     * @return array<string, mixed>
     */
    public function values(string $action): array
    {
        return [];
    }

    /**
     * Validation rules.
     *
     * @param array<string, mixed>|null $entity
     */
    public function validation(Validation $validation, string $action, ?array $entity): Validation
    {
        return $validation;
    }
}
