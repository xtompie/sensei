<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Symfony\Component\Console\Input\ArrayInput;

class CallCommand
{
    public function __construct(
        protected Context $context,
    ) {
    }

    /**
     * @param string $command
     * @param array<string,string> $params
     * @return integer
     */
    public function __invoke(string $command, array $params): int
    {
        return $this->context->application()->doRun(
            input: new ArrayInput(array_merge(
                ['command' => $command],
                $params,
            )),
            output: $this->context->output(),
        );
    }
}
