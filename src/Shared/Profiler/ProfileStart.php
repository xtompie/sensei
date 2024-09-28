<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Timer\Timer;
use DateTime;

class ProfileStart
{
    public function __construct(
        private Data $data,
        private Timer $timer,
    ) {
    }

    public function __invoke(): void
    {
        $this->timer->start();
        $this->data->add(type: 'shared.profiler.start', data: ['at' => (new DateTime())->format('Y-m-d H:i:s.u')]);
    }

    public function timer(): Timer
    {
        return $this->timer;
    }
}
