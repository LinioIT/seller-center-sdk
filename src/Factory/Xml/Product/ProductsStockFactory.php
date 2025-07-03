<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Model\Product\ProductsStock;
use SimpleXMLElement;

class ProductsStockFactory
{
    public static function make(SimpleXMLElement $xml): ProductsStock
    {
        $warehouse = new ProductsStock();

        foreach ($xml->Stocks->SellerWarehouses->Warehouse as $warehouseXml) {
            $warehouseByProduct = ProductStockFactory::make($warehouseXml);
            $warehouse->add($warehouseByProduct);
        }

        return $warehouse;
    }
}
