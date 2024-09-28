<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use DateTime;

class ProfileStop
{
    public function __construct(
        private Data $data,
        private ProfileStart $profileStart,
    ) {
    }

    public function __invoke(): void
    {
        $this->data->add(type: 'shared.profiler.stop', data: [
            'at' => (new DateTime())->format('Y-m-d H:i:s.u'),
            'duration' => $this->profileStart->timer()->get(),
        ]);
    }
}
