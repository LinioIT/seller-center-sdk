<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Order;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\V2\Factory\Xml\Order\OrdersFactory;
use Linio\SellerCenter\V2\Model\Order\Order;
use Linio\SellerCenter\V2\Model\Order\Orders;
use SimpleXMLElement;

class OrdersTest extends LinioTestCase
{
    public function testItReturnsACollectionOfOrders(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderResponse());

        $orders = OrdersFactory::make($simpleXml);

        $orderList = $orders->all();

        $order = $orders->findByOrderId(4687808);

        $this->assertInstanceOf(Orders::class, $orders);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertContainsOnlyInstancesOf(Order::class, $orderList);

        foreach ($orderList as $order) {
            $this->assertInstanceOf(Order::class, $order);
            $this->assertNull($order->getOrderItems());
        }
    }

    public function testItReturnNullWithAInvalidOrderId(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderResponse());

        $orders = OrdersFactory::make($simpleXml);

        $order = $orders->findByOrderId(12);

        $this->assertNull($order);
    }

    public function getOrderResponse(string $schema = 'Order/Orders.xml'): string
    {
        return $this->getSchemaV2($schema);
    }

    public function simpleXmlElementsWithoutAParameter(): array
    {
        return [
            ['OrderId'],
            ['OrderNumber'],
            ['OrderItems'],
        ];
    }
}
