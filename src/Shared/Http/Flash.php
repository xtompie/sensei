<?php

declare(strict_types=1);

namespace App\Shared\Http;

class Flash
{
    public function __construct(
        protected SessionProperty $sessionProperty,
    ) {
        $this->sessionProperty = $sessionProperty->withProperty('shared.flash');
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
        $this->sessionProperty->add(['msg' => $msg, 'type' => $type, 'format' => $format]);

        return $this;
    }

    /**
     * @return array<array{msg:string,type:string,format:string}>
     */
    public function pull(): array
    {
        $flashes = $this->sessionProperty->pull();
        if (!is_array($flashes)) {
            return [];
        }
        $flashes = array_filter($flashes, function ($flash) {
            return is_array($flash)
                && isset($flash['msg'], $flash['type'], $flash['format'])
                && is_string($flash['msg'])
                && is_string($flash['type'])
                && is_string($flash['format'])
            ;
        });
        return $flashes;
    }
}
