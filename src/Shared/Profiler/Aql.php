<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Container\Container;
use App\Shared\Kernel\Debug;
use Xtompie\Aql\Aql as BaseAql;
use Xtompie\Aql\Platform;
use Xtompie\Aql\Result;

class Aql extends BaseAql
{
    public function __construct(
        protected Platform $platform,
        protected Debug $debug,
    ) {
    }

    /**
     * @param array<string, mixed> $aql
     */
    public function __invoke(array $aql): Result
    {
        if ($this->debug->__invoke()) {
            $result = parent::__invoke($aql);
            Container::container()->get(ProfileAql::class)->__invoke($aql, $result);
            return $result;
        }

        return parent::__invoke($aql);
    }
}
