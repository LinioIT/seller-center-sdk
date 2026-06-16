<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use Linio\SellerCenter\Contract\CollectionInterface;

class ProductsStock implements CollectionInterface
{
    /**
     * @var ProductStock[]
     */
    protected $collection = [];

    /**
     * @return ProductStock[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(ProductStock $productStock): void
    {
        $this->collection[] = $productStock;
    }
}
