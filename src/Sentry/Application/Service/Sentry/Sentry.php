<?php

declare(strict_types=1);

namespace App\Sentry\Application\Service\Sentry;

use App\Sentry\Application\Model\Voter;
use App\Sentry\Application\Voter\AdminVoter;
use App\Sentry\Application\Voter\SuperadminVoter;
use App\Sentry\Application\Voter\TenantVoter;

class Sentry
{
    public function __construct(
        protected AdminVoter $adminVoter,
        protected SuperadminVoter $superadminVoter,
        protected TenantVoter $tenantVoter,
    ) {
    }

    /**
     * @return array<Voter>
     */
    private function voters(): array
    {
        return [
            $this->adminVoter,
            $this->superadminVoter,
            $this->tenantVoter,
        ];
    }

    public function __invoke(string $rid): bool
    {
        foreach ($this->voters() as $voter) {
            if ($voter->__invoke($rid)) {
                return true;
            }
        }

        return false;
    }
}
