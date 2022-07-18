<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Transformer\Order\OrderItemsTransformer;

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

    public function testItReturnsACollectionOfOrderItemsBySetStatus(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $orderItems = OrderItemsFactory::makeFromStatus($simpleXml->Body);

        $this->assertInstanceOf(OrderItems::class, $orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems->all());
    }

    public function testTransformsAOrderItemsObjectIntoAnXmlRepresentation(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $orderItems = OrderItemsFactory::make($simpleXml->Body);

        $orderItemsXml = OrderItemsTransformer::orderItemsImeiAsXml($orderItems);

        $orderItems = $orderItems->all();
        $orderItem = !empty($orderItems) ? reset($orderItems) : [];

        $this->assertEquals($orderItem->getOrderItemId(), (int) $orderItemsXml->OrderItem->OrderItemId);
        $this->assertEquals($orderItem->getImei(), $orderItemsXml->OrderItem->Imei);
    }
}
