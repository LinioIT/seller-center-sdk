<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Model\Product\ProductData;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class ProductDataFactory
{
    private const XML_MODEL = 'ProductData';
    private const REQUIRED_FIELDS = [
        'PackageHeight',
        'PackageWidth',
        'PackageLength',
        'PackageWeight',
    ];

    public static function make(SimpleXMLElement $element): ProductData
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);
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
