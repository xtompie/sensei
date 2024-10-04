<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Registry\Http;
use App\Shared\Http\Route\Method;
use App\Shared\Http\Route\Path;
use App\Shared\Kernel\AppDir;
use Exception;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RegexIterator;
use SplFileInfo;
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
     * @return Generator<class-string>
     */
    private function classesUsingRegistry(): Generator
    {
        yield from Http::controllers();
    }

    /**
     * @return Generator<class-string>
     */
    private function classesUsingFind(): Generator
    {
        $src = $this->appDir->__invoke() . '/src';
        $directory = new RecursiveDirectoryIterator($src);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/Controller\.php$/');
        $cutStart = strlen($src . '/');
        $cutEnd = strlen('.php');
        foreach ($files as $file) {
            if (!$file instanceof SplFileInfo) {
                continue;
            }
            if (!$file->isFile()) {
                continue;
            }
            $class = substr($file->getPathname(), $cutStart);
            $class = substr($class, 0, -$cutEnd);
            $class = str_replace('/', '\\', $class);
            $class = 'App\\' . $class;
            if (!class_exists($class)) {
                continue;
            }
            if (!in_array(Controller::class, class_implements($class))) {
                continue;
            }
            yield $class;
        }
    }

    /**
     * @return Generator<class-string>
     */
    private function classes(): Generator
    {
        yield from $this->classesUsingRegistry();
        yield from $this->classesUsingFind();
    }

    /**
     * @return Generator<ControllerMeta>
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
            $controller = $this->controllerUsingStatic($class);
            if (!$controller) {
                $controller = $this->controllerUsingAttributes($class);
            }
            if (!$controller) {
                throw new Exception("Controller $class cannot be resolved using meta or attributes.");
            }
            yield $controller;
        }
    }

    private static function controllerUsingStatic(string $class): ?ControllerMeta
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->implementsInterface(ControllerWithMeta::class)) {
            return null;
        }

        $controller = $class::controllerMeta();
        if (!$controller instanceof ControllerMeta) {
            return null;
        }

        $controller->setController($class);

        return $controller;
    }

    private function controllerUsingAttributes(string $class): ?ControllerMeta
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

        return new ControllerMeta(
            path: $path,
            controller: $class,
            methods: $methods ?: ['GET'],
            defaults: $defaults
        );
    }
}
