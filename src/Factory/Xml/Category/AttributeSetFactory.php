<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\AttributeSet;
use Linio\SellerCenter\Model\Category\Categories;
use SimpleXMLElement;

class AttributeSetFactory
{
    public static function make(SimpleXMLElement $element): AttributeSet
    {
        if (!property_exists($element, 'AttributeSetId')) {
            throw new InvalidXmlStructureException('AttributeSet', 'AttributeSetId');
        }

        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('AttributeSet', 'Name');
        }

        if (!property_exists($element, 'GlobalIdentifier')) {
            throw new InvalidXmlStructureException('AttributeSet', 'GlobalIdentifier');
        }

        if (!property_exists($element, 'Categories')) {
            throw new InvalidXmlStructureException('AttributeSet', 'Categories');
        }

        if (!empty($element->Categories)) {
            $categories = CategoriesFactory::make($element);
        } else {
            $categories = new Categories();
        }

        return new AttributeSet((int) $element->AttributeSetId, (string) $element->Name, (string) $element->GlobalIdentifier, $categories);
    }
}
