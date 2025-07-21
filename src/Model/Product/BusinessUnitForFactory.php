<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

class BusinessUnitForFactory extends BusinessUnit
{
    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }
}
