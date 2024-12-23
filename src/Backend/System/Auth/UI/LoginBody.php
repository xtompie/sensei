<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use Xtompie\Typed\Email;
use Xtompie\Typed\NotBlank;

class LoginBody
{
    public function __construct(
        #[NotBlank()]
        #[Email()]
        private string $email,
        #[NotBlank()]
        private string $password,
    ) {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
