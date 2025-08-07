<?php

declare(strict_types=1);

namespace App\Shared\Messenger;

use App\Shared\Container\Container;
use App\Shared\Kernel\File;
use App\Shared\Kernel\Source;
use App\Shared\Optimize\OptimizeDir;
use App\Shared\Optimize\Optimizer;
use Generator;
use ReflectionClass;
use ReflectionMethod;

class SageDiscoverer implements Optimizer
{
    /**
     * @param array<class-string, array<string>>|null $methods
     * @param array<class-string, array<object>> $instances
     */
    public function __construct(
        private Source $source,
        private OptimizeDir $optimizeDir,
        private ?array $methods = null,
        private array $instances = [],
    ) {
    }

    private function cache(): string
    {
        return $this->optimizeDir->__invoke() . '/' . preg_replace('/[^_A-Za-z0-9]/', '_', static::class) . '.php';
    }

    public function optimize(): void
    {
        File::write(
            filename: $this->cache(),
            data: '<?php return ' . var_export($this->discover(), true) . ';'
        );
    }

    private function initMethods(): void
    {
        if (file_exists($this->cache())) {
            $this->methods = require $this->cache();
        } else {
            $this->methods = $this->discover();
        }
    }

    /**
     * @return array<class-string, array<string>>
     */
    private function discover(): array
    {
        $index = [];

        foreach ($this->source->classes(instanceof: Sage::class, suffix: 'Sage') as $sage) {
            $reflection = new ReflectionClass($sage);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                // Skip magic methods and methods without parameters
                if (str_starts_with($method->getName(), '__')) {
                    continue;
                }

                $parameters = $method->getParameters();
                if (empty($parameters)) {
                    continue;
                }

                $messageParameter = $parameters[0];
                $messageType = $messageParameter->getType();

                if ($messageType instanceof \ReflectionNamedType && !$messageType->isBuiltin()) {
                    $messageClass = $messageType->getName();
                    /** @var class-string $messageClass */
                    $index[$messageClass][] = $sage . '::' . $method->getName();
                }
            }
        }

        foreach ($index as $messageClass => &$sageHandlers) {
            usort($sageHandlers, function ($a, $b) {
                [$classA] = explode('::', $a);
                [$classB] = explode('::', $b);
                $priorityA = is_subclass_of($classA, Priority::class) ? $classA::priority() : 0;
                $priorityB = is_subclass_of($classB, Priority::class) ? $classB::priority() : 0;
                return $priorityB <=> $priorityA;
            });
        }
        unset($sageHandlers);

        return $index;
    }

    /**
     * @param class-string $message
     * @return Generator<int, string>
     */
    private function methods(string $message): Generator
    {
        if ($this->methods === null) {
            $this->initMethods();
        }

        if (isset($this->methods[$message])) {
            yield from $this->methods[$message];
        }
    }

    /**
     * @param class-string $message
     * @return Generator<int, array{object, string}>
     */
    public function instances(string $message): Generator
    {
        if (!array_key_exists($message, $this->instances)) {
            $this->instances[$message] = [];
            $container = Container::container();

            foreach ($this->methods($message) as $methodSignature) {
                [$sageClass, $methodName] = explode('::', $methodSignature);
                $sageInstance = $container->get($sageClass);
                $this->instances[$message][] = [$sageInstance, $methodName];
            }
        }

        yield from $this->instances[$message];
    }
}
