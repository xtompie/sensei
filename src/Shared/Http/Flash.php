<?php

declare(strict_types=1);

namespace App\Shared\Http;

class Flash
{
    public function __construct(
        protected Session $session,
        protected string $namespace = 'shared.flash',
    ) {
    }

    public function withNamespace(string $namespace): static
    {
        $new = clone $this;
        $new->namespace = $namespace;
        return $new;
    }

    /**
     * @return array<array{msg:string,type:string,format:string}>
     */
    public function __invoke(): array
    {
        return $this->pull();
    }

    public function add(string $msg, string $type = 'info', string $format = 'text'): static
    {
        $this->session->push(
            key: $this->namespace,
            value: ['msg' => $msg, 'type' => $type, 'format' => $format]
        );

        return $this;
    }

    /**
     * @return array<array{msg:string,type:string,format:string}>
     */
    public function pull(): array
    {
        $flashes = $this->session->pull($this->namespace);
        if (!is_array($flashes)) {
            return [];
        }
        $flashes = array_filter($flashes, function ($flash) {
            return is_array($flash) &&
                   isset($flash['msg'], $flash['type'], $flash['format']) &&
                   is_string($flash['msg']) &&
                   is_string($flash['type']) &&
                   is_string($flash['format']);
        });
        return $flashes;
    }
}
