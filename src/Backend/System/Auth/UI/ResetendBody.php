<?php

declare(strict_types=1);

namespace App\Backend\System\Auth\UI;

use Xtompie\Result\ErrorCollection;
use Xtompie\Typed\Callback;
use Xtompie\Typed\LengthMin;
use Xtompie\Typed\NotBlank;

#[Callback('typed')]
final class ResetendBody
{
    public function __construct(
        #[NotBlank]
        #[LengthMin(8)]
        private string $password,
        private string $password_confirm,
    ) {
    }

    private function passwordIdentical(): bool
    {
        return $this->password === $this->password_confirm;
    }

    public function typed(): static|ErrorCollection
    {
        if (!$this->passwordIdentical()) {
            return ErrorCollection::ofErrorMsg('Passwords must be indentical', 'identical', 'password_confirm');
        }
        return $this;
    }

    public function password(): string
    {
        return $this->password;
    }
}
