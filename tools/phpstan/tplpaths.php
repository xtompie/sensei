<?php

function src(): string
{
    return realpath(__DIR__ . '/../../src');
}

function dest(): string
{
    return __DIR__ . '/tplpaths.neon';
}

function rel(string $path, string $base): string
{
    $relative = substr($path, strlen($base) + 1);
    return '../../src/' . str_replace('\\', '/', $relative);
}

function paths(): Generator
{
    yield from new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(src(), RecursiveDirectoryIterator::SKIP_DOTS)
    );
}

function files(): Generator
{
    foreach (paths() as $path) {
        if ($path->isFile()) {
            yield $path;
        }
    }
}

function tpl(SplFileInfo $file): bool
{
    $suffix = '.tpl.php';
    return substr($file->getFilename(), -strlen($suffix)) === $suffix;
}

function tpls(): Generator
{
    foreach (files() as $file) {
        if (tpl($file)) {
            yield rel($file->getPathname(), src());
        }
    }
}

function writeln(string $content): void
{
    file_put_contents(dest(), $content . "\n", FILE_APPEND);
}

function clear(): void
{
    file_put_contents(dest(), '');
}

clear();
writeln("parameters:");
writeln("    paths:");

foreach (tpls() as $path) {
    writeln("        - '$path'");
}