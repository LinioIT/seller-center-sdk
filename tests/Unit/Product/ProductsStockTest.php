<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\ProductsStock;
use Linio\SellerCenter\Model\Product\ProductStock;

class ProductsStockTest extends LinioTestCase
{
    public function testProductsStockModel(): void
    {
        $sku = 'TEST-123';
        $facilityId = 'GSC-12345';
        $warehouseId = 'GSC';
        $productStock = new ProductStock($sku, 1, $facilityId, $warehouseId);

        $stockOfProducts = new ProductsStock();
        $stockOfProducts->add($productStock);

        $productStockAll = $stockOfProducts->all();

        $this->assertContainsOnlyInstancesOf(ProductStock::class, $productStockAll);
        $this->assertCount(1, $productStockAll);
    }
}
