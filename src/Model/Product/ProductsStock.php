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
        $this->collection[$productStock->getSellerSku()] = $productStock;
    }

    public function findBySellerSku(string $sellerSku): ?ProductStock
    {
        if (!key_exists($sellerSku, $this->collection)) {
            return null;
        }

        return $this->collection[$sellerSku];
    }
}
