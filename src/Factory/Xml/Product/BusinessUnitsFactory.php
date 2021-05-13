<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Exception;
use Linio\SellerCenter\Model\Product\BusinessUnits;
use SimpleXMLElement;

class BusinessUnitsFactory
{
    public static function make(SimpleXMLElement $xml): BusinessUnits
    {
        $businessUnits = new BusinessUnits();

        foreach ($xml->BusinessUnits->BusinessUnit as $item) {
            try {
                $businessUnit = BusinessUnitFactory::make($item);
            } catch (Exception $e) {
                continue;
            }

            $businessUnits->add($businessUnit);
        }

        return $businessUnits;
    }
}
