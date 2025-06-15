<?php

function src(): string
{
    return realpath(__DIR__ . '/../../src');
}

function dest(): string
{
    return __DIR__ . '/modules.list.yaml';
}

function subdirs(): Generator
{
    $base = src();
    foreach (new DirectoryIterator($base) as $item) {
        if ($item->isDir() && !$item->isDot()) {
            yield $item->getFilename();
        }
    }
}

function layer(string $name): string
{
    return "    - { name: $name, collectors: [ { type: directory, value: src/$name/.* } ] }";
}

function clear(): void
{
    file_put_contents(dest(), '');
}

function out(string $line): void
{
    file_put_contents(__DIR__ . '/modules.list.yaml', $line, FILE_APPEND);
}

clear();
out("deptrac:\n");
out("  layers:\n");

foreach (subdirs() as $module) {
    out(layer($module) . "\n");
}
