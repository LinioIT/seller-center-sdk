<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\ProductsStock;
use Linio\SellerCenter\Model\Product\ProductStock;

class ProductsStockTest extends LinioTestCase
{
    public function testFindsAndReturnTheStockProductBySellerSku(): void
    {
        $sku = 'TEST-123';
        $facilityId = 'GSC-12345';
        $warehouseId = 'GSC';
        $productStock = new ProductStock($sku, 1, $facilityId, $warehouseId);

        $stockOfProducts = new ProductsStock();
        $stockOfProducts->add($productStock);

        $findBySellerSku = $stockOfProducts->findBySellerSku($sku);

        $this->assertInstanceOf(ProductsStock::class, $stockOfProducts);
        $this->assertContainsOnlyInstancesOf(ProductStock::class, $stockOfProducts->all());
        $this->assertInstanceOf(ProductStock::class, $findBySellerSku);
        $this->assertEquals($sku, $productStock->getSellerSku());
    }

    public function testFindsAndReturnNullWhenSellerSkuNotExist(): void
    {
        $sku = 'TEST-123';
        $facilityId = 'GSC-12345';
        $warehouseId = 'GSC';
        $productStock = new ProductStock($sku, 1, $facilityId, $warehouseId);

        $stockOfProducts = new ProductsStock();
        $stockOfProducts->add($productStock);

        $findBySellerSku = $stockOfProducts->findBySellerSku('anotherSku');

        $this->assertInstanceOf(ProductsStock::class, $stockOfProducts);
        $this->assertContainsOnlyInstancesOf(ProductStock::class, $stockOfProducts->all());
        $this->assertNull($findBySellerSku);
    }
}
