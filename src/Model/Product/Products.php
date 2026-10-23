<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use Linio\SellerCenter\Contract\CollectionInterface;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;

class Products implements CollectionInterface
{
    /**
     * @var ProductInterface[]
     */
    protected $collection = [];

    public function findBySellerSku(string $sellerSku): ?ProductInterface
    {
        if (!key_exists($sellerSku, $this->collection)) {
            return null;
        }

        return $this->collection[$sellerSku];
    }

    /**
     * @return ProductInterface[]
     */
    public function searchByName(string $name): array
    {
        $result = [];

        foreach ($this->collection as $product) {
            if ($product->getName() == $name) {
                $result[] = $product;
            }
        }

        return $result;
    }

    /**
     * @return ProductInterface[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(ProductInterface $product): void
    {
        $this->collection[$product->getSellerSku()] = $product;
    }
}
