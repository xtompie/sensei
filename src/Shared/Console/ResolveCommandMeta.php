<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Console\Signature\Argument as SignatureArgument;
use App\Shared\Console\Signature\Description as SignatureDescription;
use App\Shared\Console\Signature\Name as SignatureName;
use App\Shared\Console\Signature\Option as SignatureOption;
use App\Shared\Console\Signature\Signature;
use Exception;
use ReflectionAttribute;
use ReflectionClass;

class ResolveCommandMeta
{
    public function __construct(
        private CommandDiscoverOptimizer $commands,
    ) {
    }

    /**
     * @param class-string<Command> $class
     */
    public function __invoke($class): CommandMeta
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
            if ($attr instanceof SignatureName) {
                $name = (string) $attr;
            }
            if ($attr instanceof SignatureDescription) {
                $description = (string) $attr;
            }
            if ($attr instanceof SignatureArgument) {
                $arguments[] = $attr->toArgument();
            }
            if ($attr instanceof SignatureOption) {
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
