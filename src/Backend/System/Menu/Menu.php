<?php

declare(strict_types=1);

namespace App\Backend\System\Menu;

use App\Backend\System\Resource\Pilot\Pilots;
use App\Sentry\Application\Service\Sentry\Sentry;

class Menu
{
    public function __construct(
        private Pilots $pilots,
        private Sentry $sentry,
    ) {
    }

    /**
     * @return array<int,array{label:string,resource:string,url:string}>
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
     * @return array{label:string,resource:string,url:string}
     */
    private function resourceLink(string $resource): array
    {
        $pilot = $this->pilots->get($resource);
        $link = $pilot->link('index');
        return [
            'label' => $pilot->title('index') ?? $pilot::resource(),
            'resource' => $resource,
            'url' => $link['url'],
        ];
    }
}
