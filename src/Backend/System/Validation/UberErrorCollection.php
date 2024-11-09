<?php

declare(strict_types=1);

namespace App\Backend\System\Validation;

use Xtompie\Result\Error;
use Xtompie\Result\ErrorCollection;

final class UberErrorCollection
{
    public static function of(?ErrorCollection $errors): static
    {
        return new static($errors ?: ErrorCollection::ofEmpty());
    }

    /**
     * @param array<string, bool> $handled
     */
    public function __construct(
        protected ErrorCollection $errors,
        protected array $handled = [],
    ) {
    }

    public function space(string $space): ErrorCollection
    {
        $this->handled[$space] = true;

        return $this->errors->filterBySpace($space);
    }

    /**
     * @param string[] $spaces
     */
    public function spaces(array $spaces): ErrorCollection
    {
        foreach ($spaces as $space) {
            $this->handled[$space] = true;
        }

        return $this->errors->filter(fn (Error $error) => in_array($error->space(), $spaces));
    }

    public function rest(): ErrorCollection
    {
        return $this->errors->filter(fn (Error $error) => !isset($this->handled[$error->space()]));
    }
}
