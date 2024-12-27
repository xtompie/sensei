<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use Xtompie\Typed\Email;
use Xtompie\Typed\NotBlank;

class ResetBody
{
    public function __construct(
        #[NotBlank()]
        #[Email()]
        private string $email,
    ) {
    }

    public function email(): string
    {
        return $this->email;
    }
}
