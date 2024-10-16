<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

final class PilotRegistry
{
    /**
     * @param array<string, Pilot>|null $map
     */
    public function __construct(
        private PilotDiscoverer $pilotDiscoverer,
        private ?array $map = null,
    ) {
    }

    private function map(string $name): Pilot
    {
        if ($this->map === null) {
            $this->map = [];
            foreach ($this->pilotDiscoverer->instances() as $pilot) {
                $this->map[$pilot::resource()] = $pilot;
            }
        }

        return $this->map[$name];
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function __call(string $name, array $arguments = []): Pilot
    {
        return $this->map($name);
    }

    public function __get(string $name): Pilot
    {
        return $this->map($name);
    }
}
