<?php

declare(strict_types=1);

namespace App\Backend\System\Flash;

use App\Shared\Http\Flash as BaseFlash;

class Flash extends BaseFlash
{
    public function __construct(
        protected BaseFlash $flash,
    ) {
        $this->flash = $flash->withNamespace('backend.flash');
    }

    /**
     * @return array<string, mixed>
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
     * @return array<string, mixed>
     */
    public function pull(): array
    {
        return $this->flash->pull();
    }
}
