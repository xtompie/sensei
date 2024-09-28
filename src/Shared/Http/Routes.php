<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Registry\Http;
use App\Shared\Http\Route\Method;
use App\Shared\Http\Route\Path;
use App\Shared\Kernel\AppDir;
use Generator;
use ReflectionClass;
use ReflectionNamedType;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class Routes
{
    public function __construct(
        private AppDir $appDir,
        private ?RouteCollection $source = null,
        private ?RouteCollection $routes = null,
    ) {
    }

    public function optimize(): void
    {
        file_put_contents($this->cache(), $this->dumper()->dump());
    }

    public function routes(): RouteCollection
    {
        if ($this->routes === null) {
            $this->routes = new RouteCollection();
            foreach ($this->compiled() as $name => $routeData) {
                if (
                    !isset($routeData['path']) || !is_string($routeData['path'])
                    || !isset($routeData['defaults']) || !is_array($routeData['defaults'])
                ) {
                    continue;
                }
                $this->routes->add($name, new Route(
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

    private function dumper(): CompiledUrlMatcherDumper
    {
        return new CompiledUrlMatcherDumper($this->source());
    }

    private function cache(): string
    {
        return $this->appDir->__invoke() . '/optimize/http_router.php';
    }

    private function source(): RouteCollection
    {
        if ($this->source === null) {
            $this->source = new RouteCollection();
            foreach ($this->controllers() as $controller) {
                if (!$controller->controller()) {
                    continue;
                }
                $this->source->add(
                    $controller->controller(),
                    new Route(
                        path: $controller->path(),
                        defaults: [
                            ...$controller->defaults(),
                            '_controller' => $controller->controller(),
                        ],
                        methods: $controller->methods(),
                    ),
                );
            }
        }
        return $this->source;
    }

    /**
     * @return Generator<Controller>
     */
    private function controllers(): Generator
    {
        foreach (Http::controllers() as $class) {
            if (!class_exists($class)) {
                return throw new \InvalidArgumentException('Controller must be a valid class-string.');
            }

            $controller = $this->controllerUsingStatic($class);

            if (!$controller) {
                $controller = $this->controllerUsingAttributes($class);
            }

            if ($controller === null) {
                continue;
            }

            yield $controller;
        }
    }

    private function controllerUsingStatic(string $class): ?Controller
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);

        if (!$reflectionClass->hasMethod('controller')) {
            return null;
        }

        $method = $reflectionClass->getMethod('controller');

        if (!$method->isPublic() || !$method->isStatic()) {
            return null;
        }

        if ($method->getNumberOfRequiredParameters() > 0) {
            return null;
        }

        $returnType = $method->getReturnType();

        if (!$returnType instanceof ReflectionNamedType || $returnType->isBuiltin()) {
            return null;
        }

        $controller = $class::controller();

        if (!$controller instanceof Controller) {
            return null;
        }

        $controller->setController($class);

        return $controller;
    }

    private function controllerUsingAttributes(string $class): ?Controller
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->hasMethod('__invoke')) {
            return null;
        }

        $path = null;
        $methods = [];
        $defaults = [];

        foreach ($reflectionClass->getMethod('__invoke')->getAttributes() as $attribute) {
            $attr = $attribute->newInstance();
            if ($attr instanceof Path) {
                $path = $attr->path;
            }
            if ($attr instanceof Method) {
                $methods[] = (string) $attr;
            }
        }

        if ($path === null) {
            return null;
        }

        return new Controller(
            path: $path,
            controller: $class,
            methods: $methods ?: ['GET'],
            defaults: $defaults
        );
    }
}
