<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Validation\Validation;
use App\Shared\Container\Container;

abstract class AbstractPilot implements Pilot
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    protected function ctrl(): Ctrl
    {
        return Container::container()->get(Ctrl::class);
    }

    protected function repository(): Repository
    {
        return Container::container()->get(RepositoryRegistry::class)->__call(static::resource());
    }

    /**
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

    public function name(): string
    {
        return static::resource();
    }

    /**
     * @return array<string>
     */
    public function selection(): array
    {
        return ['id'];
    }

    /**
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
     * @return array<string, mixed>
     */
    public function values(string $action): array
    {
        return [];
    }

    /**
     * @param null|array<string, mixed> $entity
     */
    public function validation(Validation $validation, string $action, ?array $entity): Validation
    {
        return $validation;
    }
}
