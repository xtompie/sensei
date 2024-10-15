<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Backend\System\Ctrl\Ctrl;
use App\Shared\Container\Container;
use App\Shared\Http\Controller;
use App\Shared\Http\ControllerMeta;
use App\Shared\Http\ControllerWithMeta;
use App\Shared\Http\Response;
use Xtompie\Collection\Collection;

abstract class AbstractIndexController implements Controller, ControllerWithMeta
{
    public static function resource(): string
    {
        return array_slice(explode('\\', static::class), -2, 1)[0];
    }

    public static function action(): string
    {
        return 'index';
    }

    public static function controllerMeta(): ControllerMeta
    {
        return new ControllerMeta(path: '/backend/resource/' . static::resource());
    }

    protected function ctrl(): Ctrl
    {
        return Container::container()->get(Ctrl::class);
    }

    protected function repository(): Repository
    {
        return Container::container()->get(RepositoryRegistry::class)->__call(static::resource());
    }

    protected function pilot(): Pilot
    {
        return Container::container()->get(PilotRegistry::class)->__call(static::resource());
    }

    protected function init(): ?Response
    {
        return $this->ctrl()->init(
            sentry: $this->sentryInit(),
        );
    }

    protected function sentryInit(): string
    {
        return 'backend.resource.' . static::resource() . '.action.' . static::action();
    }

    protected function sentryProp(string $prop): string
    {
        return 'backend.resource.' . static::resource() . '.action.' . static::action() . ".prop.$prop";
    }

    /**
     * @param array<int, array<string, mixed>> $values
     * @param array<string, mixed> $where
     * @return array<string, mixed>
     */
    protected function vm(
        array $values,
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
            'field' => '/src/Backend/System/Resource/Field/field.html.twig',
            'fields' => '/src/Backend/Resource/' . static::resource() . '/fields.html.twig',
            'filter' => '/src/Backend/System/Resource/Filter/Filter.html.twig',
            'filters' => $this->filters() ? '/src/Backend/Resource/' . static::resource() . '/filters.html.twig' : null,
            'limit' => $limit,
            'more' => $this->pilot()->more(action: static::action()),
            'offset' => $offset,
            'order' => $order,
            'resource' => static::resource(),
            'title' => $this->pilot()->title(action: static::action()),
            'values' => $values,
            'where' => $where,
        ];
    }

    protected function tpl(): string
    {
        return '/src/Backend/System/Resource/Action/' . static::action() . '.tpl.php';
    }

    /**
     * @param array<int, array<string, mixed>> $values
     * @param array<string, mixed> $where
     */
    protected function view(
        array $values,
        array $where,
        ?string $order,
        int $limit,
        int $offset,
        int $all,
    ): Response {
        return Response::tpl($this->tpl(), $this->vm(
            values: $values,
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
        $values = $this->repository()->findAll(
            where: $where ?: null,
            order: $order,
            limit: $limit,
            offset: $offset
        );

        return $this->view(
            values: $values,
            where: $where,
            order: $order,
            limit: $limit,
            offset: $offset,
            all: $all,
        );
    }
}
