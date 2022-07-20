<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Order\OrderItemTest;
use SimpleXMLElement;

class OrderItemTransfomerTest extends LinioTestCase
{
    public function testItCreatesOrderItemImeiAsXML(): void
    {
        $orderItemTest = new OrderItemTest();
        $simpleXml = simplexml_load_string($orderItemTest->createXmlStringForOrderItems());

        $orderItem = OrderItemFactory::make($simpleXml);

        $orderItem->setImei('weqweqwe-123123');

        $xml = new SimpleXMLElement('<Request/>');
        OrderItemTransformer::orderItemImeiAsXml($xml, $orderItem);

        $expectedXml = $this->getSchema('Order/SetImeiRequest.xml');
        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }

    public function testItCreatesOrderItemImeiAsXMLWithoutNullProperties(): void
    {
        $orderItemTest = new OrderItemTest();
        $simpleXml = simplexml_load_string($orderItemTest->createXmlStringForOrderItems());

        $orderItem = OrderItemFactory::make($simpleXml);

        $orderItem->setImei(null);

        $xml = new SimpleXMLElement('<Request/>');
        OrderItemTransformer::orderItemImeiAsXml($xml, $orderItem);

        $expectedXml = $this->getSchema('Order/SetImeiRequest.xml');
        $expectedXml = str_replace('<Imei>weqweqwe-123123</Imei>', '', $expectedXml);

        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }
}
