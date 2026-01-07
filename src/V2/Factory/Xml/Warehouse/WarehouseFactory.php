<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Factory\Xml\Warehouse;

use Linio\SellerCenter\V2\Model\Warehouse\Warehouse;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class WarehouseFactory
{
    private const XML_MODEL = 'Warehouse';
    private const REQUIRED_FIELDS = [
        'FacilityId',
        'SellerWarehouseId',
    ];

    public static function make(SimpleXMLElement $xml): Warehouse
    {
        XmlStructureValidator::validateStructure($xml, self::XML_MODEL, self::REQUIRED_FIELDS);

        $id = (string) $xml->FacilityId;
        $status = (string) $xml->SellerWarehouseId;

        return new Warehouse($id, $status);
    }
}
