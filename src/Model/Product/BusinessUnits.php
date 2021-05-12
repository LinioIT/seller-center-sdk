<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use Linio\SellerCenter\Contract\CollectionInterface;

class BusinessUnits implements CollectionInterface
{
    /**
     * @var BusinessUnit[]
     */
    protected $collection = [];

    public function findByOperatorCode(string $operatorCode): ?BusinessUnit
    {
        if (!key_exists($operatorCode, $this->collection)) {
            return null;
        }

        return $this->collection[$operatorCode];
    }

    public function searchByBusinessUnit(string $businessUnit): array
    {
        $result = [];

        foreach ($this->collection as $aBusinessUnit) {
            if ($aBusinessUnit->getBusinessUnit() == $businessUnit) {
                $result[] = $aBusinessUnit;
            }
        }

        return $result;
    }

    public function all(): array
    {
        return $this->collection;
    }

    public function add(BusinessUnit $businessUnit): void
    {
        $this->collection[$businessUnit->getOperatorCode()] = $businessUnit;
    }
}
