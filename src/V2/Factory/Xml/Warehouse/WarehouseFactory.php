<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Factory\Xml\Warehouse;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\V2\Model\Warehouse\Warehouse;
use SimpleXMLElement;

class WarehouseFactory
{
    public static function make(SimpleXMLElement $xml): Warehouse
    {
        if (!property_exists($xml, 'FacilityId')) {
            throw new InvalidXmlStructureException('Warehouse', 'FacilityId');
        }

        if (!property_exists($xml, 'SellerWarehouseId')) {
            throw new InvalidXmlStructureException('Warehouse', 'SellerWarehouseId');
        }

        $id = (string) $xml->FacilityId;
        $status = (string) $xml->SellerWarehouseId;

        return new Warehouse($id, $status);
    }
}
