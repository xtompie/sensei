<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Aop\Aop;
use App\Shared\Container\Container;
use App\Shared\Kernel\Debug;
use App\Shared\Profiler\ProfileConsoleCommand;
use Exception;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xtompie\Result\Error;
use Xtompie\Result\ErrorCollection;
use Xtompie\Result\Result;

class Bridge extends SymfonyCommand
{
    /**
     * @param Context $context
     * @param Debug $debug
     * @param Command $command
     */
    public function __construct(
        protected Command $command,
        protected Context $context,
        protected Debug $debug,
        protected Output $output,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->context->push(
            application: $this->getApplication(),
            input: $input,
            output: $output,
        );

        if ($this->debug->__invoke()) {
            Container::container()->get(ProfileConsoleCommand::class)->__invoke($this, $input);
        }

        if (!$this->command->command()) {
            throw new Exception('Invalid command');
        }

        $command = Container::container()->get($this->command->command());
        if (!is_callable($command)) {
            throw new Exception('Invalid command');
        }

        $args = Container::container()->callArgs([$this->command->command(), '__invoke'], $this->args($input));
        $result = Aop::aop(
            method: "{$this->command->command()}::__invoke",
            args: $args,
            main: fn (...$args) => $command(...$args),
        );

        $exitCode = $this->result($result);

        $this->context->pop();

        return $exitCode;
    }

    private function result(mixed $result): int
    {
        if ($result instanceof Result) {
            return $this->output->result($result);
        }

        if ($result instanceof ErrorCollection) {
            return $this->output->result(Result::ofErrors($result));
        }

        if ($result instanceof Error) {
            return $this->output->result(Result::ofError($result));
        }

        if (is_bool($result)) {
            return $result ? 0 : 1;
        }

        if (is_int($result)) {
            return $result;
        }

        return $this->context->exitCode();
    }

    /**
     * @return array<string, mixed>
     */
    private function args(InputInterface $input): array
    {
        $args = $input->getArguments();

        foreach ($input->getOptions() as $name => $value) {
            if ($input->hasParameterOption('--' . $name)) {
                if ($input->getOption($name) === null) {
                    $args[$name] = true;
                } elseif ($input->getOption($name) === '') {
                    $args[$name] = '';
                } else {
                    $args[$name] = $value;
                }
            }
        }

        return $args;
    }
}
