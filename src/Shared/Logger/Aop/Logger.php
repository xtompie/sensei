<?php

declare(strict_types=1);

namespace App\Shared\Logger\Aop;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Logger\Logger as LoggerService;
use App\Shared\Timer\Timer;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Logger implements Advice
{
    /**
     * @var array<string,int>
     */
    private static array $limits = [];

    public function __construct(
        private string $name,
        private bool $result = false,
        private bool $args = false,
        private bool $method = false,
        private bool $time = false,
        private ?string $custom = null,
        private bool $whenResultIsFalse = false,
        private bool $whenResultIsNull = false,
        private ?string $whenResultIsInstanceOf = null,
        private ?string $whenResultIsNotInstanceOf = null,
        private ?int $limit = null,
    ) {
    }

    public function __invoke(Invocation $invocation, LoggerService $logger): mixed
    {
        $timer = $this->time ? Timer::launch() : null;
        $result = $invocation();

        if ($this->isWhenOk($result) && $this->isLimitOk($invocation)) {
            $this->log($invocation, $logger, $result, $timer);
        }

        return $result;
    }

    protected function isWhenOk(mixed $result): bool
    {
        if ($this->whenResultIsFalse) {
            return $result === false;
        }

        if ($this->whenResultIsNull) {
            return $result === null;
        }

        if ($this->whenResultIsInstanceOf) {
            return is_object($result) && is_a($result, $this->whenResultIsInstanceOf);
        }

        if ($this->whenResultIsNotInstanceOf) {
            if (!is_object($result)) {
                return true;
            }
            if (!is_a($result, $this->whenResultIsNotInstanceOf)) {
                return true;
            }
            return false;
        }

        return true;
    }

    protected function log(Invocation $invocation, LoggerService $logger, mixed $result, ?Timer $timer): void
    {
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

        $logger->withName($this->name)->__invoke((string) json_encode($info));
    }

    protected function isLimitOk(Invocation $invocation): bool
    {
        if ($this->limit === null) {
            return true;
        }

        $method = $invocation->method();

        if (!isset(static::$limits[$method])) {
            static::$limits[$method] = 0;
        }

        static::$limits[$method]++;

        return static::$limits[$method] <= $this->limit;
    }
}
