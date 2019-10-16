<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Json;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Model\Webhook\Event;
use Linio\SellerCenter\Model\Webhook\EventCallback;

class EventCallbackFactory
{
    public static function make(string $element): EventCallback
    {
        $item = Json::decode($element);

        $event = new Event($item['event'], null);

        return new EventCallback($event, $item['payload']);
    }
}
