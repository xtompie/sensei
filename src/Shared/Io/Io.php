<?php

declare(strict_types=1);

namespace App\Shared\Io;

use Xtompie\Result\Error;
use Xtompie\Result\ErrorCollection;

class Io
{
    public function __construct(
        private Data $data,
        private Errors $errors,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function data(array $data): void
    {
        $this->data->set($data);
    }

    public function errors(ErrorCollection $errors): void
    {
        $this->errors->errors($errors);
    }

    public function error(Error $error): void
    {
        $this->errors->error($error);
    }
}
