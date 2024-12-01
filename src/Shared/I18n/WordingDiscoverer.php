<?php

declare(strict_types=1);

namespace App\Shared\I18n;

use App\Shared\Kernel\Discoverer;

/**
 * @extends Discoverer<Wording>
 */
class WordingDiscoverer extends Discoverer
{
    protected function instanceof(): string
    {
        return Wording::class;
    }

    protected function suffix(): string
    {
        return 'Wording';
    }
}
