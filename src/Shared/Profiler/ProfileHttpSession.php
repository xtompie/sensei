<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Http\Session;

class ProfileHttpSession
{
    public function __construct(
        private Data $data,
        private Session $session,
    ) {
    }

    public function start(): void
    {
        $this->data->add(type: 'shared.http.session.start', data: $this->session->all());
    }

    public function stop(): void
    {
        $this->data->add(type: 'shared.http.session.stop', data: $this->session->all());
    }
}
