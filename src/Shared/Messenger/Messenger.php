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
        foreach ($this->subscribers->instances($message::class) as [$subscriber, $methodName]) {
            /** @var object $subscriber */
            /** @var string $methodName */
            if (!method_exists($subscriber, $methodName)) {
                continue;
            }
            Aop::aop(
                method: $subscriber::class . '::' . $methodName,
                args: [$message],
                main: fn () => $subscriber->$methodName($message),
            );
        }
    }
}
