<?php

declare(strict_types=1);

namespace App\Shared\Console;

use App\Shared\Console\Signature\Crontab;
use App\Shared\Console\Signature\Name;
use App\Shared\Kernel\AppDir;
use ReflectionAttribute;
use ReflectionClass;

#[Name('app:crontab')]
class CrontabCommand implements Command
{
    public function __construct(
        private AppDir $appDir,
        private CommandDiscoverOptimizer $commands,
        private Output $output,
        private ResolveCommandMeta $resolveCommandMeta,
    ) {
    }

    public function __invoke(AppDir $appDir, Output $output): void
    {
        foreach ($this->list() as $line) {

            $dir = escapeshellarg($appDir->__invoke());
            $expression = $line['crontab']->expression();
            $command = $line['meta']->name();
            $stdout = preg_replace('/[^a-zA-Z0-9]/', '_', $command);
            $stderr = $stdout . '_error';

            $output->writeln(
                "$expression cd $dir && php console $command"
                . " >> var/log/{$stdout}-\$(date +\%Y-\%m-\%d).log"
                . " 2>> var/log/{$stderr}-\$(date +\%Y-\%m-\%d).log"
            );
        }
    }

    /**
     * @return array<array{meta: CommandMeta, crontab: Crontab}>
     */
    private function list(): array
    {
        $list = [];
        foreach ($this->commands->classes() as $class) {
            $crontab = null;
            foreach ((new ReflectionClass($class))->getAttributes(Crontab::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
                $crontab = $attribute->newInstance();
            }
            if (!$crontab) {
                continue;
            }
            $list[] = [
                'meta' => $this->resolveCommandMeta->__invoke($class),
                'crontab' => $crontab,
            ];
        }

        return $list;
    }
}
