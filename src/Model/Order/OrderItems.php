<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;

class OrderItems implements CollectionInterface, JsonSerializable
{
    /**
     * @var OrderItem[]
     */
    protected $collection;

    public function findByOrderItemId(int $orderItemId): ?OrderItem
    {
        if (!key_exists($orderItemId, $this->collection)) {
            return null;
        }

        return $this->collection[$orderItemId];
    }

    public function all(): array
    {
        return $this->collection;
    }

    public function add(OrderItem $orderItem): void
    {
        $this->collection[$orderItem->getOrderItemId()] = $orderItem;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return array_values($this->collection);
    }
}
