<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\Application;

use App\Sentry\System\Role;
use App\Shared\Type\Time;

class Auth
{
    /**
     * @param array{id:string,email:string,password:string,reset_token:string,reset_at:string,role:string} $data
     */
    public function __construct(
        private array $data,
    ) {
    }

    public function id(): string
    {
        return $this->data['id'];
    }

    public function email(): string
    {
        return $this->data['email'];
    }

    public function passwordEquals(string $password): bool
    {
        return $this->data['password'] !== ''
            && password_verify($password, $this->data['password'])
        ;
    }

    public function resetToken(): string
    {
        return $this->data['reset_token'];
    }

    public function resetAt(): ?Time
    {
        return $this->data['reset_at'] ? Time::fromDateTimeString($this->data['reset_at']) : null;
    }

    public function role(): Role
    {
        return Role::from($this->data['role']);
    }
}
