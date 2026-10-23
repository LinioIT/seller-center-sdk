<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use Linio\SellerCenter\Contract\CollectionInterface;

class Warehouses implements CollectionInterface
{
    /**
     * @var Warehouse[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(Warehouse $warehouse): void
    {
        $this->collection[$warehouse->getFacilityId()] = $warehouse;
    }
}
