<?php

declare(strict_types=1);

namespace App\Shared\Monitor\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Container\Container;
use Attribute;
use RuntimeException;
use Xtompie\Monitor\Monitor as MonitorMonitor;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Monitor implements Advice
{
    /**
     * @param class-string<object> $class
     */
    public function __construct(
        private string $class,
        private string $property,
        private bool $up = false,
        private bool $down = false,
        private bool $whenResultTrue = false,
        private bool $whenResultFalse = false,
        private bool $whenResultNull = false,
        private bool $whenResultNotNull = false,
    ) {
    }

    public function __invoke(Invocation $invocation): mixed
    {
        $result = $invocation();

        if ($this->match($result)) {
            $monitor = Container::container()->get($this->class);
            if (!$monitor instanceof MonitorMonitor) {
                throw new RuntimeException('Monitor must implement Monitor interface');
            }
            if ($this->up) {
                $monitor->up($this->property);
            } elseif ($this->down) {
                $monitor->down($this->property);
            }
        }

        return $result;
    }

    private function match(mixed $result): bool
    {
        if ($this->whenResultTrue && $result === true) {
            return true;
        }

        if ($this->whenResultFalse && $result === false) {
            return true;
        }

        if ($this->whenResultNull && $result === null) {
            return true;
        }

        if ($this->whenResultNotNull && $result !== null) {
            return true;
        }

        return false;
    }
}
