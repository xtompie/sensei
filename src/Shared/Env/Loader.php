<?php

declare(strict_types=1);

namespace App\Shared\Env;

use App\Shared\Kernel\AppDir;
use Generator;

final class Loader
{
    public function __construct(
        private AppDir $appDir,
    ) {
    }

    /**
     * @return array<string,string>
     */
    public function __invoke(): array
    {
        $envs = [];
        $dir = $this->appDir->__invoke();
        foreach ($this->files() as $file) {
            $path = "$dir/$file";
            if (!is_file($path)) {
                continue;
            }
            $parsed = parse_ini_file($path);
            if ($parsed === false) {
                continue;
            }
            $envs = array_merge($envs, $parsed);
        }
        return $envs;
    }

    /**
     * @return Generator<string>
     */
    private function files(): Generator
    {
        yield '.env';
    }
}
