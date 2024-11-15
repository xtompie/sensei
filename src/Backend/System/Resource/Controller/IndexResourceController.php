<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Controller;

use App\Backend\System\Ctrl\Ctrl;
use App\Backend\System\Resource\Pilot\Pilots;
use App\Backend\System\Resource\Pilot\ResourcePilot;
use App\Backend\System\Resource\Repository\Repositories;
use App\Backend\System\Resource\Repository\ResourceRepository;
use App\Shared\Container\Container;
use App\Shared\Http\Controller;
use App\Shared\Http\ControllerDefinition;
use App\Shared\Http\HasControllerDefinition;
use App\Shared\Http\Response;
use Xtompie\Collection\Collection;

abstract class IndexResourceController implements Controller, HasControllerDefinition
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    public static function action(): string
    {
        return 'index';
    }

    public static function controllerDefinition(): ControllerDefinition
    {
        return new ControllerDefinition(path: '/backend/resource/' . strtolower(static::resource()));
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

    protected function sentryInit(): string
    {
        return $this->pilot()->sentry(action: static::action());
    }

    /**
     * @param array<int, array<string, mixed>> $entities
     * @param array<string, mixed> $where
     * @return array<string, mixed>
     */
    protected function vm(
        array $entities,
        array $where,
        ?string $order,
        int $limit,
        int $offset,
        int $all,
    ): array {
        return [
            'action' => static::action(),
            'all' => $all,
            'breadcrumb' => $this->pilot()->breadcrumb(action: static::action()),
            'filters' => $this->filters() ? '/src/Backend/Resource/' . static::resource() . '/Filters.tpl.php' : null,
            'limit' => $limit,
            'mode' => 'index',
            'more' => $this->pilot()->more(action: static::action()),
            'offset' => $offset,
            'order' => $order,
            'resource' => static::resource(),
            'title' => $this->pilot()->title(action: static::action()),
            'entities' => $entities,
            'where' => $where,
        ];
    }

    protected function tpl(): string
    {
        return '/src/Backend/System/Resource/Controller/Controller.tpl.php';
    }

    /**
     * @param array<int, array<string, mixed>> $entities
     * @param array<string, mixed> $where
     */
    protected function view(
        array $entities,
        array $where,
        ?string $order,
        int $limit,
        int $offset,
        int $all,
    ): Response {
        return Response::tpl($this->tpl(), $this->vm(
            entities: $entities,
            where: $where,
            order: $order,
            limit: $limit,
            offset: $offset,
            all: $all,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function whereQuery(): array
    {
        return Collection::of($this->ctrl()->query())
            ->only($this->filters())
            ->filter()
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    protected function whereStatic(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function where(): array
    {
        return [
            ...$this->whereQuery(),
            ...$this->whereStatic(),
        ];
    }

    protected function orderDefault(): ?string
    {
        return null;
    }

    /**
     * @return array<string>
     */
    protected function orders(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function orderQueryAvailable(): array
    {
        return array_reduce($this->orders(), function (array $carry, string $item) {
            $carry[] = $item . ':asc';
            $carry[] = $item . ':desc';
            return $carry;
        }, []);
    }

    protected function orderQuery(): ?string
    {
        $query = $this->ctrl()->query();
        if (!isset($query['order'])) {
            return null;
        }
        if (!is_string($query['order'])) {
            return null;
        }

        $order = $query['order'];
        return in_array($order, $this->orderQueryAvailable()) ? $order : null;
    }

    protected function order(): ?string
    {
        return $this->orderQuery() ?? $this->orderDefault();
    }

    protected function limit(): int
    {
        return 10;
    }

    protected function page(): int
    {
        $query = $this->ctrl()->query();
        if (!isset($query['page'])) {
            return 0;
        }
        if (!is_string($query['page'])) {
            return 0;
        }

        return intval($query['page']);
    }

    protected function offset(): int
    {
        return $this->page() * $this->limit();
    }

    protected function selection(): ?Response
    {
        $query = $this->ctrl()->query();
        $cancel = isset($query['_selection_cancel']);
        $result = isset($query['_selection_result']) && is_array($query['_selection_result']) ? $query['_selection_result'] : null;

        if ($cancel) {
            return $this->ctrl()->selection()->cancel();
        }
        if ($result) {
            return $this->ctrl()->selection()->result(
                resource: static::resource(),
                ids: $result,
            );
        }
        return null;
    }

    public function __invoke(): Response
    {
        $init = $this->init();
        if ($init) {
            return $init;
        }

        $selection = $this->selection();
        if ($selection) {
            return $selection;
        }

        $where = $this->where();
        $order = $this->order();
        $limit = $this->limit();
        $offset = $this->offset();
        $all = $this->repository()->count(where: $where ?: null);
        $entities = $this->repository()->findAll(
            where: $where ?: null,
            order: $order,
            limit: $limit,
            offset: $offset
        );

        return $this->view(
            entities: $entities,
            where: $where,
            order: $order,
            limit: $limit,
            offset: $offset,
            all: $all,
        );
    }
}
