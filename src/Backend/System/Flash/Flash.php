<?php

declare(strict_types=1);

namespace App\Backend\System\Flash;

use App\Shared\Http\Flash as BaseFlash;

class Flash
{
    public function __construct(
        protected BaseFlash $flash,
    ) {
        $this->flash = $flash->withNamespace('backend.flash');
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
        $this->flash->add(msg: $msg, type: $type, format: $format);

        return $this;
    }

    /**
     * @return array<array{msg:string,type:string,format:string}>
     */
    public function pull(): array
    {
        return $this->flash->pull();
    }
}
