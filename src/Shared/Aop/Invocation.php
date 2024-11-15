<?php

declare(strict_types=1);

namespace App\Shared\Aop;

use App\Shared\Container\Container;

class Invocation
{
    /**
     * @param array<Advice> $advices
     * @param string $method
     * @param array<mixed,mixed> $args
     * @param callable $main
     */
    public function __construct(
        protected array $advices,
        protected string $method,
        protected array $args,
        protected $main,
    ) {
    }

    public function __invoke(): mixed
    {
        if ($this->advices) {
            $new = clone $this;
            $advice = array_shift($new->advices);
            if ($advice === null) {
                throw new \RuntimeException('No advice to invoke');
            }
            if (!is_callable($advice)) {
                throw new \RuntimeException('Advice must be callable');
            }
            $args = Container::container()->callArgs([$advice, '__invoke'], ['invocation' => $new]);
            return $advice(...$args);
        }

        return ($this->main)(...$this->args);
    }

    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return array<mixed,mixed> $args
     */
    public function args(): array
    {
        return $this->args;
    }

    /**
     * @param array<string,mixed> $args
     * @return static
     */
    public function withArgs(array $args): static
    {
        $new = clone $this;
        $new->args = $args;
        return $new;
    }

    public function hash(): string
    {
        return sha1(serialize([$this->method(), $this->args()]));
    }
}
