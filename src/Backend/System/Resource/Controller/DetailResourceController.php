<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Controller;

use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Resource\Pilot\ResourcePilot;
use App\Backend\System\Resource\Pilot\ResourcePilotRegistry;
use App\Backend\System\Resource\Repository\ResourceRepository;
use App\Backend\System\Resource\Repository\ResourceRepositoryRegistry;
use App\Shared\Container\Container;
use App\Shared\Http\Controller;
use App\Shared\Http\ControllerMeta;
use App\Shared\Http\ControllerWithMeta;
use App\Shared\Http\Response;

abstract class DetailResourceController implements Controller, ControllerWithMeta
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    public static function action(): string
    {
        return 'detail';
    }

    public static function controllerMeta(): ControllerMeta
    {
        return new ControllerMeta(path: '/backend/resource/' . static::resource() . '/' . static::action() . '/{id}');
    }

    protected function ctrl(): Ctrl
    {
        return Container::container()->get(Ctrl::class);
    }

    protected function repository(): ResourceRepository
    {
        return Container::container()->get(ResourceRepositoryRegistry::class)->__call(static::resource());
    }

    protected function pilot(): ResourcePilot
    {
        return Container::container()->get(ResourcePilotRegistry::class)->__call(static::resource());
    }

    protected function init(): ?Response
    {
        return $this->ctrl()->init(
            sentry: $this->sentryInit(),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function findEntity(string $id): ?array
    {
        return $this->repository()->findById($id);
    }

    protected function sentryInit(): string
    {
        return $this->pilot()->sentry(action: static::action());
    }

    protected function sentryEntity(string $id): string
    {
        return $this->pilot()->sentry(action: static::action(), id: $id);
    }

    /**
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function augument(array $value): array
    {
        return $value;
    }

    /**
     * @param array<string, mixed> $entity
     * @return array<string, mixed>
     */
    protected function vm(array $entity): array
    {
        return [
            'action' => static::action(),
            'breadcrumb' => $this->pilot()->breadcrumb(action: static::action(), entity: $entity),
            'entity' => $entity,
            'mode' => 'detail',
            'more' => $this->pilot()->more(action: static::action(), entity: $entity),
            'resource' => static::resource(),
            'title' => $this->pilot()->title(action: static::action(), entity: $entity),
            'value' => $this->augument(value: $entity),
        ];
    }

    protected function tpl(): string
    {
        return '/src/Backend/System/Resource/Controller/Controller.tpl.php';
    }

    /**
     * @param array<string, mixed> $entity
     */
    protected function view(array $entity): Response
    {
        return Response::tpl($this->tpl(), $this->vm(
            entity: $entity,
        ));
    }

    public function __invoke(string $id): Response
    {
        $init = $this->init();
        if ($init) {
            return $init;
        }

        $entity = $this->findEntity(id: $id);
        if (!$entity) {
            return $this->ctrl()->notFound();
        }

        if (!$this->ctrl()->sentry($this->sentryEntity(id: $id))) {
            return $this->ctrl()->forbidden();
        }

        return $this->view(entity: $entity);
    }
}
