<?php

declare(strict_types=1);

namespace App\Shared\Profiler\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Kernel\Debug;
use App\Shared\Profiler\Data;
use App\Shared\Timer\Timer;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Profiler implements Advice
{
    public function __construct(
        private string $type = 'shared.profiler.debug',
        private bool $result = false,
        private bool $args = false,
        private bool $method = false,
        private bool $time = false,
        private ?string $custom = null,
    ) {
    }

    public function __invoke(Invocation $invocation, Debug $debug, Data $data): mixed
    {
        if (!$debug->__invoke()) {
            return $invocation();
        }

        $timer = $this->time ? Timer::launch() : null;
        $result = $invocation();

        $info = [];

        if ($this->custom) {
            $info['custom'] = $this->custom;
        }

        if ($this->method) {
            $info['method'] = $invocation->method();
        }

        if ($this->args) {
            $info['args'] = $invocation->args();
        }

        if ($timer) {
            $info['time'] = $timer->get();
        }

        if ($this->result) {
            $info['result'] = $result;
        }

        $data->add(type: $this->type, data: $info);

        return $result;
    }
}
