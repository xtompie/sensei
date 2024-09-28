<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class ProfileConsoleCommand
{
    public function __construct(
        private Data $data,
    ) {
    }

    public function __invoke(Command $command, InputInterface $input): void
    {
        $this->data->add(type: 'shared.console.command', data: $command->getName());
        $this->data->add(type: 'shared.console.input.arguments', data: $input->getArguments());
        $this->data->add(type: 'shared.console.input.options', data: $input->getOptions());
    }
}
