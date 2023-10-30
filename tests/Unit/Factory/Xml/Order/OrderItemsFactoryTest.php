<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Transformer\Order\OrderItemsTransformer;

class OrderItemsFactoryTest extends LinioTestCase
{
    public function testTransformsAOrderItemsArrayIntoAnXmlRepresentation(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $orderItems = OrderItemsFactory::make($simpleXml->Body);

        $orderItemsXml = OrderItemsTransformer::orderItemsImeiAsXml($orderItems->all());

        $orderItems = $orderItems->all();
        $orderItem = !empty($orderItems) ? reset($orderItems) : [];

        $this->assertEquals($orderItem->getOrderItemId(), (int) $orderItemsXml->OrderItem->OrderItemId);
        $this->assertEquals($orderItem->getImei(), $orderItemsXml->OrderItem->Imei);
    }

    public function testItReturnsACollectionOfOrderItemsBySetStatus(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $orderItems = OrderItemsFactory::makeFromStatus($simpleXml->Body);

        $this->assertInstanceOf(OrderItems::class, $orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems->all());
    }

    public function testItReturnsACollectionOfOrderItemsFromImeiStatus(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema('Order/SetImeiResponse.xml'));

        $orderItems = OrderItemsFactory::makeFromImeiStatus($simpleXml->Body);

        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
    }
}
