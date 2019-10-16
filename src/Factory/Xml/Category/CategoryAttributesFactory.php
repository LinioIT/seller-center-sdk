<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\CategoryAttributes;
use SimpleXMLElement;

class CategoryAttributesFactory
{
    public static function make(SimpleXMLElement $xml): CategoryAttributes
    {
        if (!property_exists($xml, 'Attribute')) {
            throw new InvalidXmlStructureException('CategoryAttributes', 'Attribute');
        }

        $attributes = new CategoryAttributes();

        foreach ($xml->Attribute as $item) {
            if (!empty($item)) {
                $category = CategoryAttributeFactory::make($item);
                $attributes->add($category);
            }
        }

        return $attributes;
    }
}
