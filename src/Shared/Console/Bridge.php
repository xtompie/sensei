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

class Bridge extends SymfonyCommand
{
    /**
     * @param Context $context
     * @param Debug $debug
     * @param Command $command
     */
    public function __construct(
        protected Context $context,
        protected Debug $debug,
        protected Command $command,
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

        $args = Container::container()->callArgs([$this->command->command(), '__invoke']);
        $exitCode = Aop::aop(
            method: "{$this->command->command()}::__invoke",
            args: $args,
            main: fn (...$args) => $command(...$args),
        );

        if (!is_int($exitCode)) {
            $exitCode = $this->context->exitCode();
        }
        $this->context->pop();

        return $exitCode;
    }
}
