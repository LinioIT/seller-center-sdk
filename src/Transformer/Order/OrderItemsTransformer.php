<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Order;

use Linio\SellerCenter\Model\Order\OrderItems;
use SimpleXMLElement;

class OrderItemsTransformer
{
    public static function orderItemsImeiAsXml(OrderItems $OrderItems): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($OrderItems->all() as $orderItem) {
            OrderItemTransformer::orderItemImeiAsXml($xml, $orderItem);
        }

        return $xml;
    }

    public static function orderItemsImeiAsXmlString(OrderItems $orderItems): string
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($orderItems->all() as $product) {
            OrderItemTransformer::orderItemImeiAsXml($xml, $product);
        }

        return (string) $xml->asXML();
    }
}
