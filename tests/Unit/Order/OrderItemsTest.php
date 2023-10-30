<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;

class OrderItemsTest extends LinioTestCase
{
    public function testItFindsAndReturnsTheOrderItemByOrderItemId(): void
    {
        $response = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $OrderItems = OrderItemsFactory::make($response->Body);

        $OrderItem = $OrderItems->findByOrderItemId(6750999);

        $this->assertInstanceOf(OrderItem::class, $OrderItem);
        $this->assertTrue($OrderItem->getOrderItemId() == 6750999);
    }

    public function testItReturnsAnEmptyValueWhenNoOrderItemWasFound(): void
    {
        $response = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));
        $OrderItems = OrderItemsFactory::make($response->Body);

        $OrderItem = $OrderItems->findByOrderItemId(4150769);

        $this->assertNull($OrderItem);
    }
}
