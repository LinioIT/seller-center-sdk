<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\Orders;
use RuntimeException;
use SimpleXMLElement;

class OrdersItemsFactory
{
    public static function make(SimpleXMLElement $xml): Orders
    {
        $orders = new Orders();

        foreach ($xml->Orders->Order as $item) {
            if (!property_exists($item, 'OrderId')) {
                throw new InvalidXmlStructureException('Order', 'OrderId');
            }

            if (!property_exists($item, 'OrderNumber')) {
                throw new InvalidXmlStructureException('Order', 'OrderNumber');
            }

            if (!property_exists($item, 'OrderItems')) {
                throw new InvalidXmlStructureException('Order', 'OrderItems');
            }

            if (!($item instanceof SimpleXMLElement)) {
                throw new RuntimeException('Unprocessable Entity');
            }

            $orderItems = OrderItemsFactory::make($item);

            $order = Order::fromItems((int) $item->OrderId, (int) $item->OrderNumber, $orderItems);
            $orders->add($order);
        }

        return $orders;
    }
}
