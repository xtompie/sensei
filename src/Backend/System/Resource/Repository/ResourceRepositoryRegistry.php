<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

final class ResourceRepositoryRegistry
{
    /**
     * @param array<string, ResourceRepository>|null $map
     */
    public function __construct(
        private ResourceRepositoryDiscoverer $repositoryDiscoverer,
        private ?array $map = null,
    ) {
    }

    private function map(string $name): ResourceRepository
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
    public function __call(string $name, array $arguments = []): ResourceRepository
    {
        return $this->map($name);
    }

    public function __get(string $name): ResourceRepository
    {
        return $this->map($name);
    }
}
