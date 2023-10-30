<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Order;

use Linio\SellerCenter\Model\Order\OrderItem;
use SimpleXMLElement;

class OrderItemsTransformer
{
    /**
     * @param OrderItem[] $orderItems
     */
    public static function orderItemsImeiAsXml(array $orderItems): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($orderItems as $orderItem) {
            OrderItemTransformer::orderItemImeiAsXml($xml, $orderItem);
        }

        return $xml;
    }

    /**
     * @param OrderItem[] $orderItems
     */
    public static function orderItemsImeiAsXmlString(array $orderItems): string
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($orderItems as $orderItem) {
            OrderItemTransformer::orderItemImeiAsXml($xml, $orderItem);
        }

        return (string) $xml->asXML();
    }
}
