<?php

declare(strict_types=1);

namespace App\Shared\Messenger;

use App\Shared\Aop\Aop;

class Messenger
{
    public function __construct(
        private SubscriberDiscoverer $subscribers,
    ) {
    }

    public function __invoke(object $message): void
    {
        foreach ($this->subscribers->instances($message::class) as $subscriber) {
            /** @var object $subscriber */
            if (!method_exists($subscriber, '__invoke')) {
                continue;
            }
            Aop::aop(
                method: $subscriber::class . '::__invoke',
                args: [$message],
                main: fn () => $subscriber->__invoke($message),
            );
        }
    }
}
