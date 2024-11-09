<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Pilot;

final class ResourcePilotRegistry
{
    /**
     * @param array<string, ResourcePilot>|null $map
     */
    public function __construct(
        private ResourcePilotDiscoverer $pilotDiscoverer,
        private ?array $map = null,
    ) {
    }

    private function map(string $name): ResourcePilot
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
    public function __call(string $name, array $arguments = []): ResourcePilot
    {
        return $this->map($name);
    }

    public function __get(string $name): ResourcePilot
    {
        return $this->map($name);
    }
}
