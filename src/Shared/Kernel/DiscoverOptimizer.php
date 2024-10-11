<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

use App\Shared\Container\Container;
use App\Shared\Optimize\OptimizeDir;
use App\Shared\Optimize\Optimizer;
use Generator;

/**
 * @template T
 */
abstract class DiscoverOptimizer implements Optimizer
{
    /**
     * @param array<class-string<T>>|null $source
     * @param array<class-string<T>>|null $classes
     * @param array<T>|null $instances
     */
    public function __construct(
        private Discover $discover,
        private OptimizeDir $optimizeDir,
        private ?array $source = null,
        private ?array $classes = null,
        private ?array $instances = null,
    ) {
    }

    /**
     * @return class-string<T>
     */
    abstract protected function instanceof(): string;

    abstract protected function suffix(): string;

    protected function cache(): string
    {
        return $this->optimizeDir->__invoke() . '/' . preg_replace('/[^_A-Za-z0-9]/', '_', static::class) . '.php';
    }

    /**
     * @return Generator<int, class-string<T>>
     */
    protected function source(): Generator
    {
        if ($this->source === null) {
            $this->source = iterator_to_array($this->discover->classes(
                instanceof: $this->instanceof(),
                suffix: $this->suffix(),
            ));
        }

        yield from $this->source;
    }

    public function optimize(): void
    {
        file_put_contents(
            filename: $this->cache(),
            data: '<?php return ' . var_export(iterator_to_array($this->source()), true) . ';'
        );
    }

    /**
     * @return Generator<int, class-string<T>>
     */
    public function classes(): Generator
    {
        if ($this->classes === null) {
            if (file_exists($this->cache())) {
                $this->classes = iterator_to_array(require $this->cache());
            } else {
                $this->classes = iterator_to_array($this->source());
            }
        }

        yield from $this->classes;
    }

    /**
     * @return Generator<int, T>
     */
    public function instances(): Generator
    {
        if ($this->instances === null) {
            $container = Container::container();
            $this->instances = [];
            foreach ($this->classes() as $class) {
                /** @var T $service */
                /** @var class-string<object> $class */
                $service = $container->get($class);
                $this->instances[] = $service;
            }
        }

        yield from $this->instances;
    }
}
