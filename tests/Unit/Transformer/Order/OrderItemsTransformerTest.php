<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\LinioTestCase;

class OrderItemsTransfomerTest extends LinioTestCase
{
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
