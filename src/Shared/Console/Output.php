<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Container\Container;
use Symfony\Component\Console\Output\OutputInterface;
use Xtompie\Result\Error;
use Xtompie\Result\Result;

class Output
{
    public function __construct(
        protected Context $context,
    ) {
    }

    protected function output(): OutputInterface
    {
        return $this->context->output();
    }

    public function write(string $message): void
    {
        $this->output()->write($message);
    }

    public function writeln(string $message): void
    {
        $this->output()->writeln($message);
    }

    public function ln(): void
    {
        $this->output()->writeln("\n");
    }

    public function infoln(string $message): void
    {
        $this->output()->writeln("<info>$message</info>");
    }

    public function commentln(string $message): void
    {
        $this->output()->writeln("<comment>$message</comment>");
    }

    public function errorln(string $message): void
    {
        $this->output()->writeln("<error>$message</error>");
    }

    public function result(Result $result): int
    {
        if ($result->success()) {
            $value = $result->value();
            if ($value !== null) {
                if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                    $this->writeln((string) $value);
                } else {
                    $this->writeln(print_r($value, true));
                }
            }
            return 0;
        }
        $this->errorln('ERROR');
        $result->errors()->each(
            fn (Error $error) => $this->errorln(
                $error->space() . ': ' . implode(' - ', array_filter([$error->key(), $error->message()]))
            )
        );
        return 1;
    }

    /**
     * @param array<array<string, string>> $data
     * @param boolean $header
     * @param array<'center'|'left'|'right'> $align
     * @param 'default'|'compact'|'borderless'|'box'|'symfony-style-guide' $style
     * @return void
     */
    public function table(array $data, bool $header = true, array $align = [], string $style = 'default'): void
    {
        Container::container()->get(TableOutput::class)->__invoke(
            output: $this->output(),
            data: $data,
            header: $header,
            align: $align,
            style: $style,
        );
    }
}
