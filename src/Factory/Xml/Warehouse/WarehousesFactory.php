<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\Warehouses;
use SimpleXMLElement;

class WarehousesFactory
{
    public static function make(SimpleXMLElement $xml): Warehouses
    {
        $warehouses = new Warehouses();

        foreach ($xml->Warehouses->Warehouse as $warehouse) {
            $warehouses->add(WarehouseFactory::make($warehouse));
        }

        return $warehouses;
    }
}
