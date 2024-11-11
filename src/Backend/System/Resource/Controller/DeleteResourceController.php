<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Controller;

use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Resource\Pilot\ResourcePilot;
use App\Backend\System\Resource\Pilot\ResourcePilotRegistry;
use App\Backend\System\Resource\Repository\ResourceRepository;
use App\Backend\System\Resource\Repository\ResourceRepositoryRegistry;
use App\Backend\System\Validation\UberErrorCollection;
use App\Backend\System\Validation\Validation;
use App\Shared\Container\Container;
use App\Shared\Http\Controller;
use App\Shared\Http\ControllerMeta;
use App\Shared\Http\ControllerWithMeta;
use App\Shared\Http\Response;
use Xtompie\Result\ErrorCollection;
use Xtompie\Result\Result;

abstract class DeleteResourceController implements Controller, ControllerWithMeta
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    public static function action(): string
    {
        return 'delete';
    }

    public static function controllerMeta(): ControllerMeta
    {
        return new ControllerMeta(path: '/backend/resource/' . strtolower(static::resource()) . '/' . static::action() . '/{id}');
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

    protected function init(string $id): ?Response
    {
        return $this->ctrl()->init(
            sentry: $this->sentryInit($id),
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function findEntity(string $id): ?array
    {
        return $this->repository()->findById($id);
    }

    protected function sentryInit(string $id): string
    {
        return 'backend.resource.' . static::resource() . '.action.' . static::action() . ".id.$id";
    }

    protected function sentryProp(string $id, string $prop): string
    {
        return 'backend.resource.' . static::resource() . '.action.' . static::action() . ".id.$id.prop.$prop";
    }

    protected function commit(): bool
    {
        return $this->ctrl()->submit() === 'commit';
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function body(): ?array
    {
        return $this->ctrl()->body();
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function valueSentry(array $entity, array $value): array
    {
        /** @var string $id */
        $id = $entity['id'];
        foreach ($value as $prop => $v) {
            if (!$this->ctrl()->sentry($this->sentryProp(id: $id, prop: $prop))) {
                unset($value[$prop]);
            }
        }
        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    protected function valuePositiveList(): array
    {
        return $this->pilot()->values(action: static::action());
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function valuePositive(array $entity, array $value): array
    {
        $list = $this->valuePositiveList();
        if (!$list) {
            return $value;
        }

        foreach ($value as $k => $v) {
            if (!in_array($k, $list)) {
                unset($value[$k]);
            }
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function value(array $entity, array $value): array
    {
        $value = $this->valueSentry(entity: $entity, value: $value);
        $value = $this->valuePositive(entity: $entity, value: $value);
        return $value;
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function augument(array $entity, array $value): array
    {
        return $value;
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     */
    protected function validate(array $entity, array $value): Result
    {
        $validation = $this->ctrl()->validation($value);
        $validation = $this->validation(entity: $entity, validation: $validation);
        return $validation->result();
    }

    /**
     * @param array<string, mixed> $entity
     */
    protected function validation(array $entity, Validation $validation): Validation
    {
        return $validation;
    }

    protected function remove(string $id): Result
    {
        return $this->repository()->remove(id: $id);
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function vm(array $entity, array $value, ?ErrorCollection $errors = null): array
    {
        return [
            'action' => static::action(),
            'breadcrumb' => $this->pilot()->breadcrumb(action: static::action(), entity: $entity),
            'entity' => $entity,
            'errors' => UberErrorCollection::of($errors),
            'more' => $this->pilot()->more(action: static::action(), entity: $entity),
            'resource' => static::resource(),
            'title' => $this->pilot()->title(action: static::action(), entity: $entity),
            'value' => $this->augument(entity: $entity, value: $value),
        ];
    }

    /**
     * @param array<string, mixed> $entity
     */
    protected function commited(array $entity): Response
    {
        /** @var class-string $class */
        $class = 'App\Backend\Resource\\' . static::resource() . '\IndexController';
        return Response::redirectToController($class);
    }

    /**
     * @param array<string, mixed> $entity
     */
    protected function flash(array $entity): void
    {
        $this->ctrl()->flash('Deleted');
    }

    protected function tpl(): string
    {
        return '/src/Backend/System/Resource/Controller/Form.tpl.php';
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $value
     */
    protected function stagged(array $entity, array $value, ?ErrorCollection $errors = null): Response
    {
        return Response::tpl($this->tpl(), $this->vm(
            entity: $entity,
            value: $value,
            errors: $errors
        ));
    }

    public function __invoke(string $id): Response
    {
        $init = $this->init(id: $id);
        if ($init) {
            return $init;
        }

        $entity = $this->findEntity(id: $id);
        if (!$entity) {
            return $this->ctrl()->notFound();
        }

        $value = $this->value(entity: $entity, value: (array) $this->body());

        if (!$this->commit()) {
            return $this->stagged(entity: $entity, value: $value);
        }

        $validate = $this->validate(entity: $entity, value: $value);
        if ($validate->fail()) {
            return $this->stagged(entity: $entity, value: $value, errors: $validate->errors());
        }

        $save = $this->remove(id: $id);
        if ($save->fail()) {
            return $this->stagged(entity: $entity, value: $value, errors: $save->errors());
        }

        $this->flash(entity: $entity);

        return $this->commited(entity: $entity);
    }
}
