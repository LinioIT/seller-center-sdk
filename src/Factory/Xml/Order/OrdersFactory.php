<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\Orders;
use SimpleXMLElement;

class OrdersFactory
{
    public static function make(SimpleXMLElement $xml): Orders
    {
        $orders = new Orders();

        foreach ($xml->Orders->Order as $item) {
            $order = OrderFactory::make($item);
            $orders->add($order);
        }

        return $orders;
    }
}
