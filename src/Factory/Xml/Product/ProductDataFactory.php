<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Product\ProductData;
use SimpleXMLElement;

class ProductDataFactory
{
    public static function make(SimpleXMLElement $element): ProductData
    {
        if (!property_exists($element, 'ConditionType')) {
            throw new InvalidXmlStructureException('ProductData', 'ConditionType');
        }

        if (!property_exists($element, 'PackageHeight')) {
            throw new InvalidXmlStructureException('ProductData', 'PackageHeight');
        }

        if (!property_exists($element, 'PackageWidth')) {
            throw new InvalidXmlStructureException('ProductData', 'PackageWidth');
        }

        if (!property_exists($element, 'PackageLength')) {
            throw new InvalidXmlStructureException('ProductData', 'PackageLength');
        }

        if (!property_exists($element, 'PackageWeight')) {
            throw new InvalidXmlStructureException('ProductData', 'PackageWeight');
        }

        $productData = new ProductData(
            (string) $element->ConditionType,
            (float) $element->PackageHeight,
            (float) $element->PackageWidth,
            (float) $element->PackageLength,
            (float) $element->PackageWeight
        );

        foreach ($element->children() as $item) {
            $productData->add($item->getName(), (string) $item);
        }

        return $productData;
    }
}
