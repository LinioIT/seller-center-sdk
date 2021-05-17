<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use Linio\SellerCenter\Contract\CollectionInterface;

class Products implements CollectionInterface
{
    /**
     * @var BaseProduct[]
     */
    protected $collection = [];

    public function findBySellerSku(string $sellerSku): ?BaseProduct
    {
        if (!key_exists($sellerSku, $this->collection)) {
            return null;
        }

        return $this->collection[$sellerSku];
    }

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

    public function all(): array
    {
        return $this->collection;
    }

    public function add(BaseProduct $product): void
    {
        $this->collection[$product->getSellerSku()] = $product;
    }
}
