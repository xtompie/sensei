<?php

declare(strict_types=1);

namespace App\Backend\System\Menu;

use App\Backend\System\Resource\Pilot\Pilots;
use App\Backend\System\Resource\Repository\Repositories;
use App\Sentry\System\Sentry;

class Menu
{
    public function __construct(
        private Pilots $pilots,
        private Repositories $repositories,
        private Sentry $sentry,
    ) {
    }

    /**
     * @return array<array<string,string>>
     */
    public function __invoke(): array
    {
        $menu = [];

        foreach ($this->pilots->all() as $pilot) {
            if (!$this->resourceSentry($pilot::resource())) {
                continue;
            }
            $menu[] = $this->resourceLink($pilot::resource());
        }

        return $menu;
    }

    private function resourceSentry(string $resource): bool
    {
        return $this->sentry->__invoke($this->pilots->get($resource)->sentry('index'));
    }

    /**
     * @return array<string,string>
     */
    private function resourceLink(string $resource): array
    {
        $pilot = $this->pilots->get($resource);
        $repository = $this->repositories->get($resource);
        $link = $pilot->link('index');
        return [
            'label' => $pilot->title('index') ?? $pilot::resource(),
            'resource' => $resource,
            'url' => $link['url'],
            'badge' => (string) $repository->count(),
        ];
    }
}
