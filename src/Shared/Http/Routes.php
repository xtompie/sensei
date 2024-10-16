<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Registry\Http;
use App\Shared\Kernel\Source;
use App\Shared\Optimize\OptimizeDir;
use Generator;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper as SymfonyCompiledUrlMatcherDumper;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;

final class Routes
{
    public function __construct(
        private Source $source,
        private OptimizeDir $optimizeDir,
        private ResolveControllerMeta $resolveControllerMeta,
        private ?SymfonyRouteCollection $discovered = null,
        private ?SymfonyRouteCollection $routes = null,
    ) {
    }

    public function optimize(): void
    {
        file_put_contents($this->cache(), $this->dumper()->dump());
    }

    public function routes(): SymfonyRouteCollection
    {
        if ($this->routes === null) {
            $this->routes = new SymfonyRouteCollection();
            foreach ($this->compiled() as $name => $routeData) {
                if (
                    !isset($routeData['path']) || !is_string($routeData['path'])
                    || !isset($routeData['defaults']) || !is_array($routeData['defaults'])
                ) {
                    continue;
                }
                $this->routes->add($name, new SymfonyRoute(
                    path: $routeData['path'],
                    defaults: $routeData['defaults'],
                    requirements: $routeData['requirements'] ?? [],
                    options:$routeData['options'] ?? [],
                    host: $routeData['host'] ?? '',
                    schemes: $routeData['schemes'] ?? [],
                    methods: $routeData['methods'] ?? [],
                    condition: $routeData['condition'] ?? ''
                ));
            }
        }
        return $this->routes;
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function compiled(): array
    {
        if (file_exists($this->cache())) {
            return require $this->cache();
        }

        return $this->dumper()->getCompiledRoutes();
    }

    private function dumper(): SymfonyCompiledUrlMatcherDumper
    {
        return new SymfonyCompiledUrlMatcherDumper($this->discovered());
    }

    private function cache(): string
    {
        return $this->optimizeDir->__invoke() . '/' . preg_replace('/[^_A-Za-z0-9]/', '_', static::class) . '.php';
    }

    private function discovered(): SymfonyRouteCollection
    {
        if ($this->discovered === null) {
            $this->discovered = new SymfonyRouteCollection();
            foreach ($this->controllers() as $controller) {
                if (!$controller->controller()) {
                    continue;
                }
                $this->discovered->add(
                    $controller->controller(),
                    new SymfonyRoute(
                        path: $controller->path(),
                        requirements: $controller->requirements(),
                        defaults: [
                            ...$controller->defaults(),
                            '_controller' => $controller->controller(),
                        ],
                        methods: $controller->methods(),
                    ),
                );
            }
        }
        return $this->discovered;
    }

    /**
     * @return Generator<class-string>
     */
    private function classes(): Generator
    {
        yield from Http::controllers();
        yield from $this->source->classes(instanceof: Controller::class, suffix: 'Controller');
    }

    /**
     * @return Generator<int, ControllerMeta>
     */
    private function controllers(): Generator
    {
        $unique = [];
        foreach ($this->classes() as $class) {
            if (isset($unique[$class])) {
                continue;
            }
            $unique[$class] = true;
            if (!class_exists($class)) {
                return throw new \InvalidArgumentException('Controller must be a valid class-string.');
            }

            $controller = $this->resolveControllerMeta->__invoke($class);

            yield $controller;
        }
    }
}
