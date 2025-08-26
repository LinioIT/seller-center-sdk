<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Model\Product\ProductsStock;
use SimpleXMLElement;

class StocksTransformer
{
    public static function asXmlString(ProductsStock $productsStock): string
    {
        $xml = new SimpleXMLElement('<Request/>');
        $warehouseElement = $xml->addChild('Warehouse');
        foreach ($productsStock->all() as $productStock) {
            StockTransformer::asXml($warehouseElement, $productStock);
        }

        return (string) $xml->asXML();
    }
}
