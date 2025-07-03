<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Model\Product\ProductStock;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class ProductStockFactory
{
    private const XML_MODEL = 'ProductStock';
    private const REQUIRED_FIELDS = [
        'SellerSku',
        'Quantity',
    ];

    public static function make(SimpleXMLElement $xml): ProductStock
    {
        XmlStructureValidator::validateStructure($xml, self::XML_MODEL, self::REQUIRED_FIELDS);

        return new ProductStock(
            (string) $xml->SellerSku,
            (int) $xml->Quantity,
            (string) $xml->FacilityID,
            (string) $xml->SellerWarehouseId
        );
    }
}
