<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Pilot;

use Generator;
use InvalidArgumentException;

final class Pilots
{
    /**
     * @param array<string,ResourcePilot>|null $map
     */
    public function __construct(
        private PilotDiscoverer $pilotDiscoverer,
        private ?array $map = null,
    ) {
    }

    private function map(): void
    {
        $this->map = [];
        foreach ($this->pilotDiscoverer->instances() as $pilot) {
            $this->map[$pilot::resource()] = $pilot;
        }
    }

    public function get(string $resource): ResourcePilot
    {
        if ($this->map === null) {
            $this->map();
        }
        if (!isset($this->map[$resource])) {
            throw new InvalidArgumentException("Resource pilot $resource not found.");
        }
        return $this->map[$resource];
    }

    /**
     * @return Generator<int,ResourcePilot>
     */
    public function all(): Generator
    {
        if ($this->map === null) {
            $this->map();
        }

        if ($this->map === null) {
            return;
        }

        $all = array_values($this->map);

        usort($all, fn ($a, $b) => $b->priority() <=> $a->priority());

        yield from $all;
    }
}
