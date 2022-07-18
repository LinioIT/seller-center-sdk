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

        $orderItem->setImei('qweqweasd-qw213123');

        $xml = new SimpleXMLElement('<Request/>');
        OrderItemTransformer::orderItemImeiAsXml($xml, $orderItem);

        $expectedXml = $this->getSchema('Order/SetImeiRequest.xml');
        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }
}
