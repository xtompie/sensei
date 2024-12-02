<?php

declare(strict_types=1);

namespace App\Backend\System\Auth;

class User
{
    /**
     * @param array{id:string,password:string} $data
     */
    public function __construct(
        private array $data,
    ) {
    }

    public function id(): string
    {
        return $this->data['id'];
    }

    /**
     * @return array{id:string,password:string}
     */
    public function data(): array
    {
        return $this->data;
    }

    public function passwordMatches(string $password): bool
    {
        return password_verify($password, $this->data['password']);
    }
}
