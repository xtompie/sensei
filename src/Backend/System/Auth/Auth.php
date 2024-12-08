<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

class Auth
{
    /**
     * @param array{id:string} $data
     */
    public function __construct(
        private array $data,
    ) {
    }

    public function id(): string
    {
        return $this->data['id'];
    }
}
