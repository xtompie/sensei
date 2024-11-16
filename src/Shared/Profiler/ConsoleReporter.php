<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Kernel\AppDir;

final class ConsoleReporter
{
    public function __construct(
        private Data $data,
        private AppDir $appDir,
    ) {
    }

    public function __invoke(): void
    {
        $out = '';
        foreach ($this->data->get() as $v) {
            /** @var array{type:string,data:array<mixed>} $v */
            $out .= $v['type'] . ' | ' . json_encode($v['data']) . "\n";
        }

        file_put_contents($this->appDir->get() . '/tools/profiler/console.log', $out);
    }
}
