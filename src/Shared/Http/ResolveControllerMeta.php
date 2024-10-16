<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Http\Route\Method;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\Route;
use Exception;
use ReflectionAttribute;
use ReflectionClass;

final class ResolveControllerMeta
{
    /**
     * @param class-string $class
     */
    public function __invoke(string $class): ControllerMeta
    {
        $controller = $this->usingStatic($class);
        if (!$controller) {
            $controller = $this->usingAttributes($class);
        }
        if (!$controller) {
            throw new Exception("Controller $class cannot be resolved using meta or attributes.");
        }

        return $controller;
    }

    private static function usingStatic(string $class): ?ControllerMeta
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

    private function usingAttributes(string $class): ?ControllerMeta
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);

        $path = null;
        $requirements = [];
        $methods = [];
        $defaults = [];

        foreach ($reflectionClass->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $attr = $attribute->newInstance();
            if ($attr instanceof Path) {
                $path = $attr->path;
                $requirements = $attr->requirements;
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
            requirements: $requirements,
            methods: $methods ?: ['GET'],
            defaults: $defaults
        );
    }
}
