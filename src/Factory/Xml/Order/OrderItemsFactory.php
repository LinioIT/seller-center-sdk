<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use SimpleXMLElement;

class OrderItemsFactory
{
    public static function make(SimpleXMLElement $xml): OrderItems
    {
        $orderItems = new OrderItems();

        foreach ($xml->OrderItems->OrderItem as $item) {
            $orderItem = OrderItemFactory::make($item);
            $orderItems->add($orderItem);
        }

        return $orderItems;
    }

    public static function makeFromStatus(SimpleXMLElement $element): OrderItems
    {
        $orderItems = new OrderItems();
        foreach ($element->OrderItems->OrderItem as $item) {
            $orderItem = OrderItemFactory::makeFromStatus($item);
            $orderItems->add($orderItem);
        }

        return $orderItems;
    }

    /**
     * @return OrderItem[]
     */
    public static function makeFromImeiStatus(SimpleXMLElement $element): array
    {
        $orderItems = new OrderItems();
        foreach ($element->OrderItem as $item) {
            $orderItem = OrderItemFactory::makeFromImeiStatus($item);
            $orderItems->add($orderItem);
        }

        return $orderItems->all();
    }
}
