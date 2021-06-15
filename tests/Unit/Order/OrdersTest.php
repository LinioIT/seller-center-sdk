<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\OrdersFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrdersItemsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Model\Order\Orders;
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

    public function testItReturnsACollectionOfOrderItems(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderResponse('Order/OrdersItems.xml'));

        $orders = OrdersItemsFactory::make($simpleXml);

        $orderList = $orders->all();

        $order = $orders->findByOrderId(4687808);

        $this->assertInstanceOf(Orders::class, $orders);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertContainsOnlyInstancesOf(Order::class, $orderList);

        foreach ($orderList as $order) {
            $this->assertInstanceOf(Order::class, $order);
            $this->assertInstanceOf(OrderItems::class, $order->getOrderItems());
            $this->assertContainsOnlyInstancesOf(OrderItem::class, $order->getOrderItems()->all());
        }
    }

    public function testItReturnNullWithAInvalidOrderId(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderResponse());

        $orders = OrdersFactory::make($simpleXml);

        $order = $orders->findByOrderId(12);

        $this->assertNull($order);
    }

    /**
     * @dataProvider simpleXmlElementsWithoutAParameter
     */
    public function testItThrowsAExceptionWithoutAPropertyInTheXml(string $property): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a Order. The property %s should exist.',
                $property
            )
        );

        $simpleXml = new SimpleXMLElement($this->getOrderResponse('Order/OrdersItems.xml'));

        unset($simpleXml->Orders->Order->{$property});

        OrdersItemsFactory::make($simpleXml);
    }

    public function getOrderResponse(string $schema = 'Order/Orders.xml'): string
    {
        return $this->getSchema($schema);
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
