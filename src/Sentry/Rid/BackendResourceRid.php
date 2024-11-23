<?php

declare(strict_types=1);

namespace App\Sentry\Rid;

use App\Sentry\System\Rid;

class BackendResourceRid implements Rid
{
    public function __construct(
        private string $resource,
        private ?string $action = null,
        private ?string $id = null,
        private ?string $prop = null,
    ) {
    }

    public function resource(): string
    {
        return $this->resource;
    }

    public function action(): ?string
    {
        return $this->action;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function prop(): ?string
    {
        return $this->prop;
    }
}
