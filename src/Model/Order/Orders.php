<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use Linio\SellerCenter\Contract\CollectionInterface;

class Orders implements CollectionInterface
{
    /**
     * @var Order[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function findByOrderId(int $orderId): ?Order
    {
        if (!key_exists($orderId, $this->collection)) {
            return null;
        }

        return $this->collection[$orderId];
    }

    public function add(Order $order): void
    {
        $this->collection[$order->getOrderId()] = $order;
    }
}
