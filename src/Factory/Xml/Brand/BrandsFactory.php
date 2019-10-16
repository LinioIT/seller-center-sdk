<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Brand;

use Exception;
use Linio\SellerCenter\Model\Brand\Brands;
use SimpleXMLElement;

class BrandsFactory
{
    /**
     * @throws Exception
     */
    public static function make(SimpleXMLElement $xml): Brands
    {
        $brands = new Brands();

        foreach ($xml->Brands->Brand as $item) {
            $brand = BrandFactory::make($item);
            $brands->add($brand);
        }

        return $brands;
    }
}
