<?php

declare(strict_types=1);

namespace App\Sentry\Application\Voter;

use App\Sentry\Application\Model\Role;
use App\Sentry\Application\Model\Voter;
use App\Sentry\Application\Service\AllowDisallow\AllowDisallow;

class AdminVoter implements Voter
{
    public function __construct(
        protected AllowDisallow $allowDisallow,
    ) {
    }

    public function __invoke(string $resource): bool
    {
        return $this->allowDisallow->__invoke(
            $resource,
            Role::admin(),
            disallow: [],
            allow: $this->allow(),
        );
    }

    /**
     * @return string[]
     */
    private function allow(): array
    {
        return [
            'consent.*',
            'history.*',
            'transition.*',
        ];
    }
}
