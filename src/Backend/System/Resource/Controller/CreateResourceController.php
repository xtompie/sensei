<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Controller;

use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Resource\Pilot\Pilots;
use App\Backend\System\Resource\Pilot\ResourcePilot;
use App\Backend\System\Resource\Repository\Repositories;
use App\Backend\System\Resource\Repository\ResourceRepository;
use App\Backend\System\Validation\UberErrorCollection;
use App\Backend\System\Validation\Validation;
use App\Shared\Container\Container;
use App\Shared\Gen\Gen;
use App\Shared\Http\Controller;
use App\Shared\Http\ControllerMeta;
use App\Shared\Http\ControllerWithMeta;
use App\Shared\Http\Response;
use Exception;
use Xtompie\Result\ErrorCollection;
use Xtompie\Result\Result;

abstract class CreateResourceController implements Controller, ControllerWithMeta
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    public static function action(): string
    {
        return 'create';
    }

    public static function controllerMeta(): ControllerMeta
    {
        return new ControllerMeta(path: '/backend/resource/' . strtolower(static::resource()) . '/' . static::action());
    }

    protected function ctrl(): Ctrl
    {
        return Container::container()->get(Ctrl::class);
    }

    protected function repository(): ResourceRepository
    {
        return Container::container()->get(Repositories::class)->get(static::resource());
    }

    protected function pilot(): ResourcePilot
    {
        return Container::container()->get(Pilots::class)->get(static::resource());
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

    protected function sentryProp(string $prop): string
    {
        return $this->pilot()->sentry(action: static::action(), prop: $prop);
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
     * @return array<string, mixed>
     */
    protected function dummy(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function valueSentry(array $value): array
    {
        foreach ($value as $prop => $v) {
            if (!$this->ctrl()->sentry($this->sentryProp(prop: $prop))) {
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
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function valuePositive(array $value): array
    {
        $positive = $this->valuePositiveList();
        return array_filter(
            $value,
            fn ($k) => in_array($k, $positive),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function value(array $value): array
    {
        $value = $this->valueSentry(value: $value);
        $value = $this->valuePositive(value: $value);
        return $value;
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
     * @param array<string, mixed> $value
     */
    protected function validate(array $value): Result
    {
        $validation = $this->ctrl()->validation($value);
        $validation = $this->validation(validation: $validation);
        return $validation->result();
    }

    protected function validation(Validation $validation): Validation
    {
        return $this->pilot()->validation(validation: $validation, action: static::action(), entity: null);
    }

    /**
     * @param array<string, mixed> $value
     */
    protected function save(string $id, array $value): Result
    {
        return $this->repository()->save(id: $id, value: $value);
    }

    /**
     * @param array<string, mixed> $value
     * @return array<string, mixed>
     */
    protected function vm(array $value, ?ErrorCollection $errors = null): array
    {
        return [
            'action' => static::action(),
            'breadcrumb' => $this->pilot()->breadcrumb(action: static::action(), entity: null),
            'errors' => UberErrorCollection::of($errors),
            'mode' => 'form',
            'more' => $this->pilot()->more(action: static::action(), entity: null),
            'resource' => static::resource(),
            'title' => $this->pilot()->title(action: static::action()),
            'value' => $this->augument(value: $value),
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
        $this->ctrl()->flash('Created');
    }

    protected function tpl(): string
    {
        return '/src/Backend/System/Resource/Controller/Controller.tpl.php';
    }

    protected function generateId(): string
    {
        return Gen::id();
    }

    /**
     * @param array<string, mixed> $value
     */
    protected function stagged(array $value, ?ErrorCollection $errors = null): Response
    {
        return Response::tpl($this->tpl(), $this->vm(
            value: $value,
            errors: $errors,
        ));
    }

    public function __invoke(): Response
    {
        $init = $this->init();
        if ($init) {
            return $init;
        }

        $value = $this->value(value: $this->body() ?: $this->dummy());
        if (!$this->commit()) {
            return $this->stagged(value: $value);
        }

        $validate = $this->validate(value: $value);
        if ($validate->fail()) {
            return $this->stagged(value: $value, errors: $validate->errors());
        }

        $id = $this->generateId();

        $save = $this->save(id: $id, value: $value);
        if ($save->fail()) {
            return $this->stagged(value: $value, errors: $save->errors());
        }

        $entity = $this->findEntity(id: $id);

        if ($entity === null) {
            return throw new Exception('Entity not created');
        }

        $this->flash(entity: $entity);

        return $this->commited(entity: $entity);
    }
}
