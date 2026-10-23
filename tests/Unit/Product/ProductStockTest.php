<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\ProductStock;

class ProductStockTest extends LinioTestCase
{
    public function testProductStockModel(): void
    {
        $sku = 'TEST-123';
        $facilityId = 'GSC-12345';
        $warehouseId = 'GSC';

        $productStock = new ProductStock($sku, 1, $facilityId, $warehouseId);

        $this->assertInstanceOf(ProductStock::class, $productStock);
        $this->assertEquals($sku, $productStock->getSellerSku());
        $this->assertEquals(1, $productStock->getQuantity());
        $this->assertEquals($facilityId, $productStock->getFacilityId());
        $this->assertEquals($warehouseId, $productStock->getSellerWarehouseId());
    }
}
