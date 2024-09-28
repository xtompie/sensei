<?php

declare(strict_types=1);

namespace App\Shared\Env;

use Xtompie\Result\Error;
use Xtompie\Result\ErrorCollection;
use Xtompie\Result\Result;

final class Validator
{
    public function __construct(
        private Env $env,
    ) {
    }

    public function __invoke(): Result
    {
        $errors = ErrorCollection::ofEmpty();
        foreach ($this->env->entries() as $entry) {
            if ($entry->optional()) {
                continue;
            }
            if ($this->env->__invoke($entry->key()) !== '') {
                continue;
            }
            $errors = $errors->add(new Error(
                message: 'required',
                key: 'required',
                space: $entry->key(),
            ));
        }

        return Result::byErrors($errors);
    }
}
