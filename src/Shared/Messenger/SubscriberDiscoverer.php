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

class SubscriberDiscoverer implements Optimizer
{
    /**
     * @param array<class-string, array<string>>|null $methods
     * @param array<class-string, array<array{object, string}>> $instances
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

        // Scan classes ending with "Subscriber"
        foreach ($this->source->classes(instanceof: Subscriber::class, suffix: 'Subscriber') as $subscriber) {
            $this->discoverClass($subscriber, $index);
        }

        // Scan classes ending with "Sage" (for backward compatibility)
        foreach ($this->source->classes(instanceof: Subscriber::class, suffix: 'Sage') as $sage) {
            $this->discoverClass($sage, $index);
        }

        foreach ($index as $messageClass => &$subscriberHandlers) {
            usort($subscriberHandlers, function ($a, $b) {
                [$classA] = explode('::', $a);
                [$classB] = explode('::', $b);
                $priorityA = is_subclass_of($classA, Priority::class) ? $classA::priority() : 0;
                $priorityB = is_subclass_of($classB, Priority::class) ? $classB::priority() : 0;
                return $priorityB <=> $priorityA;
            });
        }
        unset($subscriberHandlers);

        return $index;
    }

    /**
     * @param class-string $handlerClass
     * @param array<class-string, array<string>> $index
     */
    private function discoverClass(string $handlerClass, array &$index): void
    {
        $reflection = new ReflectionClass($handlerClass);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            // Skip magic methods except __invoke
            if (str_starts_with($method->getName(), '__') && $method->getName() !== '__invoke') {
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
                $index[$messageClass][] = $handlerClass . '::' . $method->getName();
            }
        }
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
                [$subscriberClass, $methodName] = explode('::', $methodSignature);
                /** @var class-string $subscriberClass */
                $subscriberInstance = $container->get($subscriberClass);
                $this->instances[$message][] = [$subscriberInstance, $methodName];
            }
        }

        yield from $this->instances[$message];
    }
}
