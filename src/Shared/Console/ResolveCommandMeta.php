<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Console\Signature\Argument;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;
use App\Shared\Console\Signature\Option;
use App\Shared\Console\Signature\Signature;
use Exception;
use ReflectionAttribute;
use ReflectionClass;

final class ResolveCommandMeta
{
    /**
     * @param class-string $class
     */
    public function __invoke(string $class): CommandMeta
    {
        $command = $this->usingStatic($class);
        if (!$command) {
            $command = $this->usingAttributes($class);
        }
        if (!$command) {
            throw new Exception("Command $class cannot be resolved using meta or attributes.");
        }

        return $command;
    }

    private function usingStatic(string $class): ?CommandMeta
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->implementsInterface(CommandWithMeta::class)) {
            return null;
        }

        $command = $class::commandMeta();
        if (!$command instanceof CommandMeta) {
            return null;
        }

        $command->setCommand($class);

        return $command;
    }

    private function usingAttributes(string $class): ?CommandMeta
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($class);

        $name = null;
        $description = null;
        $arguments = [];
        $options = [];

        foreach ($reflectionClass->getAttributes(Signature::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            $attr = $attribute->newInstance();
            if ($attr instanceof Name) {
                $name = (string) $attr;
            }
            if ($attr instanceof Description) {
                $description = (string) $attr;
            }
            if ($attr instanceof Argument) {
                $arguments[] = $attr->toArgument();
            }
            if ($attr instanceof Option) {
                $options[] = $attr->toOption();
            }
        }

        if ($name === null) {
            return null;
        }

        return new CommandMeta(
            name: $name,
            command: $class,
            description: $description,
            arguments: $arguments,
            options: $options,
        );
    }
}
