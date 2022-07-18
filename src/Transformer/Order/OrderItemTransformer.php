<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Order;

use Linio\SellerCenter\Model\Order\OrderItem;
use SimpleXMLElement;

class OrderItemTransformer
{
    public static function orderItemImeiAsXml(SimpleXMLElement &$xml, OrderItem $orderItem): void
    {
        $body = $xml->addChild('OrderItem');

        self::addAttributes(
            $body,
            [
                'OrderItemId' => $orderItem->getOrderItemId(),
                'Imei' => $orderItem->getImei(),
            ]
        );
    }

    /**
     * @param mixed[] $attributes
     */
    public static function addAttributes(SimpleXMLElement $xml, array $attributes): void
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            if ($attributeValue === null) {
                continue;
            }

            $adaptedValue = (string) $attributeValue;

            if ($adaptedValue === null) {
                continue;
            }

            $encodedValue = htmlspecialchars($adaptedValue);
            $xml->addChild($attributeName, $encodedValue);
        }
    }
}
