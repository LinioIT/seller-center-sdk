<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Brand;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Brand\Brand;
use SimpleXMLElement;

class BrandFactory
{
    public static function make(SimpleXMLElement $element): Brand
    {
        if (!property_exists($element, 'BrandId')) {
            throw new InvalidXmlStructureException('Brand', 'BrandId');
        }

        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('Brand', 'Name');
        }

        if (!property_exists($element, 'GlobalIdentifier')) {
            throw new InvalidXmlStructureException('Brand', 'GlobalIdentifier');
        }

        return Brand::build(
            (int) $element->BrandId,
            (string) $element->Name,
            (string) $element->GlobalIdentifier
        );
    }
}
