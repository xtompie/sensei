<?php

declare(strict_types=1);

namespace App\Backend\System\Backend;

use App\Backend\System\Menu\Menu;
use App\Backend\System\Resource\Pilot;
use App\Backend\System\Resource\Repository;
use App\Backend\System\Resource\Selection;
use App\Shared\Http\Request;

final class Backend
{
    public function __construct(
        private Menu $menu,
        private Pilot $pilot,
        private Repository $repository,
        private Request $request,
        private Selection $selection,
    ) {
    }

    public function __invoke(): static
    {
        return $this;
    }

    /**
     * @param array<string, mixed> $query
     */
    public function alterCurrentUri(array $query): string
    {
        return $this->request->alterUri($query);
    }

    /**
     * @param array<string, mixed> $query
     */
    public function alterUri(string $url, array $query): string
    {
        [$urlPath, $urlQuery] = explode('?', $url, 2);
        if ($urlQuery !== null) {
            parse_str($urlQuery, $urlQuery);
        }
        $urlQuery = array_merge($urlQuery, $query);
        $urlQuery = http_build_query($urlQuery);

        return $urlPath . '?' . $urlQuery;
    }

    public function menu(): Menu
    {
        return $this->menu;
    }

    public function moveto(): int
    {
        $body = $this->request->body();
        if (!isset($body['_moveto'])) {
            return 0;
        }
        if (!is_string($body['_moveto'])) {
            return 0;
        }
        return intval($body['_moveto']);
    }

    public function repository(): Repository
    {
        return $this->repository;
    }

    public function pilot(): Pilot
    {
        return $this->pilot;
    }

    public function selection(): Selection
    {
        return $this->selection;
    }

    public function modal(): bool
    {
        return $this->selection()->enabled();
    }
}
