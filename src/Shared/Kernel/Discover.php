<?php

declare(strict_types=1);

namespace App\Shared\Kernel;

use App\Shared\Container\Container;
use Exception;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

class Discover
{
    public function __construct(
        private AppDir $appDir,
    ) {
    }

    /**
     * @template T
     * @param class-string<T> $implements
     * @param string $suffix
     * @return Generator<class-string<T>>
     */
    public function classes(string $implements, string $suffix): Generator
    {
        $src = $this->appDir->__invoke() . '/src';
        $directory = new RecursiveDirectoryIterator($src);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/' . $suffix . '\.php$/');
        $cutStart = strlen("$src/");
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
                throw new Exception("Class $class does not exist");
            }
            if (!in_array($implements, class_implements($class))) {
                continue;
            }
            /** @var class-string<T> $class */
            yield $class;
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $implements
     * @param string $suffix
     * @return Generator<T>
     */
    public function instances(string $implements, string $suffix): Generator
    {
        $container = Container::container();
        foreach ($this->classes(implements: $implements, suffix: $suffix) as $class) {
            yield $container->get($class);
        }
    }
}
