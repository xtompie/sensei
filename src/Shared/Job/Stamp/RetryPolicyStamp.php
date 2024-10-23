<?php

declare(strict_types=1);

namespace App\Shared\Job\Stamp;

use stdClass;

class RetryPolicyStamp implements Stamp
{

    public static function fromPrimitive(stdClass $primitive): static
    {
        return new self($primitive->delayes);
    }

    public function __construct(
        private array $delayes,
    ) {
    }

    public function delayForRetry(int $offset): ?int
    {
        return $this->delayes[$offset] ?? null;
    }

    public function toPrimitive(): stdClass
    {
        return (object) [
            'delayes' => $this->delayes,
        ];
    }

}
