<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Kernel\File;
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
        private ControllerDefinitionResolver $controllerDefinitionResolver,
        private ?SymfonyRouteCollection $routes = null,
    ) {
    }

    public function optimize(): void
    {
        File::write(filename: $this->matcherCachePath(), data: $this->dumper()->dump());
        $this->saveRouteCache();
    }

    private function saveRouteCache(): void
    {
        $cache = [];
        foreach ($this->routes() as $name => $route) {
            $cache[$name] = [
                'path' => $route->getPath(),
                'defaults' => $route->getDefaults(),
                'requirements' => $route->getRequirements(),
                'options' => $route->getOptions(),
                'host' => $route->getHost(),
                'schemes' => $route->getSchemes(),
                'methods' => $route->getMethods(),
                'condition' => $route->getCondition(),
            ];
        }
        File::write(filename: $this->routesCachePath(), data: '<?php return ' . var_export($cache, true) . ';');
    }

    private function loadRouteCache(): SymfonyRouteCollection
    {
        $routes = new SymfonyRouteCollection();
        foreach (require $this->routesCachePath() as $name => $routeData) {
            $routes->add($name, new SymfonyRoute(
                path: $routeData['path'],
                defaults: $routeData['defaults'],
                requirements: $routeData['requirements'],
                options: $routeData['options'],
                host: $routeData['host'],
                schemes: $routeData['schemes'],
                methods: $routeData['methods'],
                condition: $routeData['condition'],
            ));
        }
        return $routes;
    }

    /**
     * @return array<int,mixed>
     */
    public function compiled(): array
    {
        if (file_exists($this->matcherCachePath())) {
            return require $this->matcherCachePath();
        }

        return $this->dumper()->getCompiledRoutes();
    }

    private function dumper(): SymfonyCompiledUrlMatcherDumper
    {
        return new SymfonyCompiledUrlMatcherDumper($this->routes());
    }

    private function matcherCachePath(): string
    {
        return $this->optimizeDir->__invoke() . '/' . preg_replace('/[^_A-Za-z0-9]/', '_', static::class) . '_Matcher.php';
    }

    private function routesCachePath(): string
    {
        return $this->optimizeDir->__invoke() . '/' . preg_replace('/[^_A-Za-z0-9]/', '_', static::class) . '_Routes.php';
    }

    public function routes(): SymfonyRouteCollection
    {
        if ($this->routes === null) {
            if (file_exists($this->routesCachePath())) {
                $this->routes = $this->loadRouteCache();
            } else {
                $this->routes = $this->sourceRoutes();
            }
        }
        return $this->routes;
    }

    /**
     * @return Generator<int, ControllerDefinition>
     */
    private function controllerDefinitions(): Generator
    {
        /** @var ControllerDefinition[] $metas */
        $metas = [];
        foreach ($this->source->classes(instanceof: Controller::class, suffix: 'Controller') as $class) {
            if (!class_exists($class)) {
                return throw new \InvalidArgumentException('Controller must be a valid class-string.');
            }
            $metas[] = $this->controllerDefinitionResolver->__invoke($class);
        }

        usort($metas, function (ControllerDefinition $a, ControllerDefinition $b) {
            return $b->priority() <=> $a->priority();
        });

        yield from $metas;
    }

    private function sourceRoutes(): SymfonyRouteCollection
    {
        $routes = new SymfonyRouteCollection();
        foreach ($this->controllerDefinitions() as $controller) {
            if (!$controller->controller()) {
                continue;
            }
            $routes->add(
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

        return $routes;
    }
}
