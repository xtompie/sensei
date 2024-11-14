<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Repository;

final class Repositories
{
    /**
     * @param array<string, ResourceRepository>|null $map
     */
    public function __construct(
        private RepositoryDiscoverer $repositoryDiscoverer,
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

    public function get(string $resource): ResourceRepository
    {
        return $this->map($resource);
    }
}
