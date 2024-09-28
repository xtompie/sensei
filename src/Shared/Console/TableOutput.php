<?php

declare(strict_types=1);

namespace App\Shared\Console;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\OutputInterface;

class TableOutput
{
    /**
     * @param OutputInterface $output
     * @param array<array<string, string>> $data
     * @param boolean $header
     * @param array<'center'|'left'|'right'> $align
     * @return void
     */
    public function __invoke(
        OutputInterface $output,
        array $data,
        bool $header = true,
        array $align = [],
    ): void {
        $table = new Table($output);
        if ($header && count($data) > 0) {
            $table->setHeaders(array_keys($data[0]));
        }
        foreach ($data as $line) {
            $row = [];
            foreach ($line as $key => $val) {
                $row[] = new TableCell(
                    value: $val,
                    options: [
                        'style' => new TableCellStyle(array_filter([
                            'align' => $align[$key] ?? null,
                        ])),
                    ],
                );
            }
            $table->addRow($row);
        }
        $table->render();
    }
}
