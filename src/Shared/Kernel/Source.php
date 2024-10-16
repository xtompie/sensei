<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

use App\Shared\Container\Container;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

class Source
{
    /**
     * @param array<class-string>|null $source
     */
    public function __construct(
        private AppDir $srcDir,
        private ?array $source = null,
    ) {
    }

    /**
     * @return Generator<class-string>
     */
    private function source(): Generator
    {
        if ($this->source === null) {
            $this->source = [];
            $src = $this->srcDir->__invoke() . '/src';
            $cutStart = strlen("$src/");
            $cutEnd = strlen('.php');
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src)) as $file) {
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
                /** @var class-string $class */
                $this->source[] = $class;
            }
        }

        yield from $this->source;
    }

    /**
     * @template T
     * @param class-string<T> $instanceof
     * @param string $suffix
     * @return Generator<class-string<T>>
     */
    public function classes(string $instanceof, string $suffix): Generator
    {
        foreach ($this->source() as $class) {
            if (substr($class, -strlen($suffix)) !== $suffix) {
                continue;
            }
            if (!class_exists($class)) {
                continue;
            }
            if (!is_a($class, $instanceof, true)) {
                continue;
            }
            /** @var class-string<object> $class */
            if ((new ReflectionClass($class))->isAbstract()) {
                continue;
            }
            /** @var class-string<T> $class */
            yield $class;
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $instanceof
     * @param string $suffix
     * @return Generator<int, T>
     */
    public function instances(string $instanceof, string $suffix): Generator
    {
        $container = Container::container();
        foreach ($this->classes(instanceof: $instanceof, suffix: $suffix) as $class) {
            yield $container->get($class);
        }
    }
}
