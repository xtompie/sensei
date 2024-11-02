<?php

declare(strict_types=1);

namespace App\Shared\Messenger;

use App\Shared\Aop\Aop;

class Dispatcher
{
    public function __construct(
        private SubscriberDiscoverer $subscribers,
    ) {
    }

    public function __invoke(object $message): void
    {
        foreach ($this->subscribers->instances($message::class) as $subscriber) {
            Aop::aop(
                method: $subscriber::class . "::__invoke",
                args: [$message],
                main: fn() => $subscriber->__invoke($message),
            );
        }
    }
}
