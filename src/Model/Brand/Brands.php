<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Brand;

use Linio\SellerCenter\Contract\CollectionInterface;

class Brands implements CollectionInterface
{
    /**
     * @var Brand[]
     */
    protected $collection = [];

    public function findById(int $brandId): ?Brand
    {
        if (!key_exists($brandId, $this->collection)) {
            return null;
        }

        return $this->collection[$brandId];
    }

    /**
     * @return Brand[]
     */
    public function searchByName(string $name): array
    {
        $result = [];

        foreach ($this->collection as $brand) {
            if ($brand->getName() == $name) {
                $result[] = $brand;
            }
        }

        return $result;
    }

    /**
     * @return Brand[]
     */
    public function searchByGlobalIdentifier(string $globalIdentifier): array
    {
        $result = [];

        foreach ($this->collection as $brand) {
            if ($brand->getGlobalIdentifier() == $globalIdentifier) {
                $result[] = $brand;
            }
        }

        return $result;
    }

    /**
     * @return Brand[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(Brand $brand): void
    {
        $this->collection[$brand->getBrandId()] = $brand;
    }
}
