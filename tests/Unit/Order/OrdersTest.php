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

    public function testItThrowsAExceptionWithoutAOrderIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderId should exist.');

        $simpleXml = simplexml_load_string(
            '<Body>
                      <Orders>
                           <Order>
                                <OrderNumber>206125233</OrderNumber>
                                <OrderItems></OrderItems>
                            </Order>
                        </Orders>
                    </Body>'
        );

        OrdersItemsFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAOrderNumberInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderNumber should exist.');

        $simpleXml = simplexml_load_string(
            '<Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <OrderItems></OrderItems>
                            </Order>
                        </Orders>
                    </Body>'
        );

        OrdersItemsFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAOrderItemsInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderItems should exist.');

        $simpleXml = simplexml_load_string(
            '<Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <OrderNumber>206125233</OrderNumber>
                            </Order>
                        </Orders>
                    </Body>'
        );

        OrdersItemsFactory::make($simpleXml);
    }

    public function getOrderResponse(string $schema = 'Order/Orders.xml'): string
    {
        return $this->getSchema($schema);
    }
}
