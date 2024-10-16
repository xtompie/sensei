<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

final class RepositoryRegistry
{
    /**
     * @param array<string, Repository>|null $map
     */
    public function __construct(
        private RepositoryDiscoverer $repositoryDiscoverer,
        private ?array $map = null,
    ) {
    }

    private function map(string $name): Repository
    {
        if ($this->map === null) {
            $this->map = [];
            foreach ($this->repositoryDiscoverer->instances() as $repository) {
                $this->map[$repository::resource()] = $repository;
            }
        }

        return $this->map[$name];
    }

    /**
     * @param array<string, mixed> $arguments
     */
    public function __call(string $name, array $arguments = []): Repository
    {
        return $this->map($name);
    }

    public function __get(string $name): Repository
    {
        return $this->map($name);
    }
}
