<?php

declare(strict_types=1);

namespace App\Sentry\Application\Service\AllowDisallow;

use App\Sentry\Application\Model\Role;
use App\Sentry\Application\Model\RoleContext;

class AllowDisallow
{
    public function __construct(
        protected RoleContext $roleContext,
    ) {
    }

    /**
     * @param string[] $disallow
     * @param string[] $allow
     */
    public function __invoke(string $rid, Role $role, array $disallow, array $allow): bool
    {
        if (!$this->roleContext->equals($role)) {
            return false;
        }

        if ($this->any($disallow, $rid)) {
            return false;
        }

        return $this->any($allow, $rid);
    }

    private function match(string $pattern, string $subject): bool
    {
        $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\*', '.*', $pattern);
        $pattern = "#^$pattern$#";

        return 1 === preg_match($pattern, $subject);
    }

    /**
     * @param string[] $patterns
     */
    private function any(array $patterns, string $subject): bool
    {
        foreach ($patterns as $pattern) {
            if ($this->match($pattern, $subject)) {
                return true;
            }
        }

        return false;
    }
}
