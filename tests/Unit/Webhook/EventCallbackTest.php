<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Webhook;

use Linio\SellerCenter\Factory\Json\EventCallbackFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Webhook\EventCallback;

class EventCallbackTest extends LinioTestCase
{
    public function testItReturnsAEventCallbackFromAnJson(): void
    {
        $eventAlias = 'onOrderItemsStatusChanged';
        $orderId = 190;
        $orderItemsId1 = 2;
        $orderItemsId2 = 3;

        $json = sprintf(
            '{"event":"%s","payload":{"OrderId":%d,"OrderItemsId":[%d, %d]}}',
            $eventAlias,
            $orderId,
            $orderItemsId1,
            $orderItemsId2
        );

        $eventCallback = EventCallbackFactory::make($json);

        $this->assertInstanceOf(EventCallback::class, $eventCallback);
        $this->assertEquals($eventAlias, $eventCallback->getEvent()->getAlias());
        $this->assertEquals($orderId, $eventCallback->getPayload()['OrderId']);
        $this->assertEquals($orderItemsId1, $eventCallback->getPayload()['OrderItemsId'][0]);
        $this->assertEquals($orderItemsId2, $eventCallback->getPayload()['OrderItemsId'][1]);
    }
}
