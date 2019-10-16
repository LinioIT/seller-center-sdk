<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\AttributeSets;
use SimpleXMLElement;

class AttributesSetFactory
{
    public static function make(SimpleXMLElement $element): AttributeSets
    {
        if (empty($element->AttributeSets->AttributeSet)) {
            throw new InvalidXmlStructureException('AttributeSets', 'AttributeSet');
        }

        $attributeSets = new AttributeSets();

        foreach ($element->AttributeSets->AttributeSet as $item) {
            if (!empty($item)) {
                $attributeSet = AttributeSetFactory::make($item);
                $attributeSets->add($attributeSet);
            }
        }

        return $attributeSets;
    }
}
