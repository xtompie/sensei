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

class SubscriberDiscoverer implements Optimizer
{
    /**
     * @param array<class-string, array<class-string<Subscriber>>>|null $classes
     * @param array<class-string, array<Subscriber>> $instances
     */
    public function __construct(
        private Source $source,
        private OptimizeDir $optimizeDir,
        private ?array $classes = null,
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

    private function initClasses(): void
    {
        if (file_exists($this->cache())) {
            $this->classes = require $this->cache();
        } else {
            $this->classes = $this->discover();
        }
    }

    /**
     * @return array<class-string, array<class-string<Subscriber>>>
     */
    private function discover(): array
    {
        $index = [];

        foreach ($this->source->classes(instanceof: Subscriber::class, suffix: 'Subscriber') as $subscriber) {
            $invokeMethod = (new ReflectionClass($subscriber))->getMethod('__invoke');
            $parameters = $invokeMethod->getParameters();
            $messageParameter = $parameters[0];
            $messageType = $messageParameter->getType();

            if ($messageType instanceof \ReflectionNamedType && !$messageType->isBuiltin()) {
                $messageClass = $messageType->getName();
            } else {
                continue;
            }

            /** @var class-string $messageClass */
            $index[$messageClass][] = $subscriber;
        }

        foreach ($index as $messageClass => &$subscribers) {
            usort($subscribers, function ($a, $b) {
                $priorityA = is_subclass_of($a, Priority::class) ? $a::priority() : 0;
                $priorityB = is_subclass_of($b, Priority::class) ? $b::priority() : 0;
                return $priorityB <=> $priorityA;
            });
        }
        unset($subscribers);

        return $index;
    }

    /**
     * @param class-string $message
     * @return Generator<int, class-string<Subscriber>>
     */
    private function classes(string $message): Generator
    {
        if ($this->classes === null) {
            $this->initClasses();
        }

        if (isset($this->classes[$message])) {
            yield from $this->classes[$message];
        }
    }

    /**
     * @param class-string $message
     * @return Generator<int, Subscriber>
     */
    public function instances(string $message): Generator
    {
        if (!array_key_exists($message, $this->instances)) {
            $this->instances[$message] = [];
            $container = Container::container();
            foreach ($this->classes($message) as $subscriber) {
                $this->instances[$message][] = $container->get($subscriber);
            }
        }

        yield from $this->instances[$message];
    }
}
