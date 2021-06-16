<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\Orders;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class OrdersItemsFactory
{
    private const XML_MODEL = 'Order';
    private const REQUIRED_FIELDS = [
        'OrderId',
        'OrderNumber',
        'OrderItems',
    ];

    public static function make(SimpleXMLElement $xml): Orders
    {
        $orders = new Orders();

        foreach ($xml->Orders->Order as $item) {
            XmlStructureValidator::validateStructure($item, self::XML_MODEL, self::REQUIRED_FIELDS);

            $orderItems = OrderItemsFactory::make($item);

            $order = Order::fromItems((int) $item->OrderId, (int) $item->OrderNumber, $orderItems);
            $orders->add($order);
        }

        return $orders;
    }
}
