<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ContextItem
{
    public function __construct(
        protected Application $application,
        protected InputInterface $input,
        protected OutputInterface $output,
        protected int $exitCode = 0,
    ) {
    }

    public function application(): Application
    {
        return $this->application;
    }

    public function input(): InputInterface
    {
        return $this->input;
    }

    public function output(): OutputInterface
    {
        return $this->output;
    }

    public function exitCode(): int
    {
        return $this->exitCode;
    }

    public function updateExitCode(int $exitCode): void
    {
        $this->exitCode = $exitCode;
    }
}
