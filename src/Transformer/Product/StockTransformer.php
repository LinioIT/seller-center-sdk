<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Model\Product\ProductStock;
use SimpleXMLElement;

class StockTransformer
{
    public static function asXml(SimpleXMLElement &$warehouseElement, ProductStock $productStock): void
    {
        $stockElement = $warehouseElement->addChild('Stock');
        $stockElement->addChild('SellerSku', $productStock->getSellerSku());
        $stockElement->addChild('Quantity', (string) $productStock->getQuantity());

        $facilityId = $productStock->getFacilityId();
        $sellerWarehouseId = $productStock->getSellerWarehouseId();

        if ($facilityId !== null) {
            $stockElement->addChild('GSCFacilityId', $facilityId);
        }

        if ($sellerWarehouseId !== null) {
            $stockElement->addChild('SellerWarehouseId', $sellerWarehouseId);
        }
    }
}
