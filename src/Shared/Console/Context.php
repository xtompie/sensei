<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Context
{
    /**
     * @param array<ContextItem> $stack
     */
    public function __construct(
        protected array $stack = [],
    ) {
    }

    protected function current(): ContextItem
    {
        if (!$this->stack) {
            throw new Exception();
        }

        return $this->stack[count($this->stack) - 1];
    }

    public function push(?Application $application, InputInterface $input, OutputInterface $output): void
    {
        if (!$application instanceof Application) {
            throw new Exception();
        }

        $this->stack[] = new ContextItem(
            application: $application,
            input: $input,
            output: $output,
        );
    }

    public function pop(): void
    {
        array_pop($this->stack);
    }

    public function application(): Application
    {
        return $this->current()->application();
    }

    public function input(): InputInterface
    {
        return $this->current()->input();
    }

    public function output(): OutputInterface
    {
        return $this->current()->output();
    }

    public function exitCode(): int
    {
        return $this->current()->exitCode();
    }

    public function updateExitCode(int $status): void
    {
        $this->current()->updateExitCode($status);
    }
}
