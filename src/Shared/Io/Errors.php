<?php

declare(strict_types=1);

namespace App\Shared\Io;

use Xtompie\Result\Error;
use Xtompie\Result\ErrorCollection;

final class Errors
{
    private ErrorCollection $errors;

    /**
     * @var array<string,bool>
     */
    private array $extracted;

    public function __construct(
    ) {
        $this->errors = ErrorCollection::ofEmpty();
        $this->extracted = [];
    }

    public function __invoke(ErrorCollection $errors): void
    {
        $this->errors($errors);
    }

    public function set(ErrorCollection $errors): void
    {
        $this->errors = $errors;
        $this->extracted = [];
    }

    public function errors(ErrorCollection $errors): void
    {
        $this->set($errors);
    }

    public function addErrors(ErrorCollection $errors): void
    {
        $this->errors = $this->errors->merge($errors);
    }

    public function exist(): bool
    {
        return $this->errors->any();
    }

    public function error(Error $error): void
    {
        $this->errors(ErrorCollection::ofError($error));
    }

    public function space(string $space): ErrorCollection
    {
        $this->extracted[$space] = true;

        return $this->errors->filterBySpace($space);
    }

    /**
     * @param string[] $spaces
     */
    public function spaces(array $spaces): ErrorCollection
    {
        foreach ($spaces as $space) {
            $this->extracted[$space] = true;
        }

        return $this->errors->filter(fn (Error $error) => in_array($error->space(), $spaces));
    }

    public function rest(): ErrorCollection
    {
        return $this->errors->filter(fn (Error $error) => !isset($this->extracted[$error->space()]));
    }
}
