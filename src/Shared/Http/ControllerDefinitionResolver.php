<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Http\Route\Method;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\Priority;
use App\Shared\Http\Route\Route;
use Exception;
use ReflectionAttribute;
use ReflectionClass;

final class ControllerDefinitionResolver
{
    /**
     * @param array<string, ControllerDefinition> $cache
     */
    public function __construct(
        private array $cache = [],
    ) {
    }

    /**
     * @param class-string $class
     */
    public function __invoke(string $class): ControllerDefinition
    {
        if (!isset($this->cache[$class])) {
            $controller = $this->usingStatic($class);
            if (!$controller) {
                $controller = $this->usingAttributes($class);
            }
            if (!$controller) {
                throw new Exception("Controller $class cannot be resolved using meta or attributes.");
            }

            $this->cache[$class] = $controller;
        }

        return $this->cache[$class];
    }

    private static function usingStatic(string $class): ?ControllerDefinition
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->implementsInterface(HasControllerDefinition::class)) {
            return null;
        }

        $controller = $class::controllerDefinition();
        if (!$controller instanceof ControllerDefinition) {
            return null;
        }

        $controller->setController($class);

        return $controller;
    }

    private function usingAttributes(string $class): ?ControllerDefinition
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);

        $path = null;
        $requirements = [];
        $methods = [];
        $defaults = [];
        $priority = 0;

        foreach ($reflectionClass->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $attr = $attribute->newInstance();
            if ($attr instanceof Path) {
                $path = $attr->path;
                $requirements = $attr->requirements;
            }
            if ($attr instanceof Method) {
                $methods[] = (string) $attr;
            }
            if ($attr instanceof Priority) {
                $priority = $attr->priority;
            }
        }

        if ($path === null) {
            return null;
        }

        return new ControllerDefinition(
            path: $path,
            controller: $class,
            requirements: $requirements,
            methods: $methods ?: ['GET'],
            defaults: $defaults,
            priority: $priority,
        );
    }
}
