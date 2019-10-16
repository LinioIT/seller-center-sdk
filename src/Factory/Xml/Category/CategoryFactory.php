<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\Category;
use SimpleXMLElement;

class CategoryFactory
{
    public static function make(SimpleXMLElement $xml): Category
    {
        if (!property_exists($xml, 'CategoryId')) {
            throw new InvalidXmlStructureException('Category', 'CategoryId');
        }

        if (!property_exists($xml, 'Name')) {
            throw new InvalidXmlStructureException('Category', 'Name');
        }

        if (!property_exists($xml, 'GlobalIdentifier')) {
            throw new InvalidXmlStructureException('Category', 'GlobalIdentifier');
        }

        $category = Category::build(
            (int) $xml->CategoryId,
            (string) $xml->Name,
            (string) $xml->GlobalIdentifier,
            (int) $xml->AttributeSetId
        );

        if (!empty($xml->Children->Category)) {
            foreach ($xml->Children->Category as $child) {
                $childCategory = CategoryFactory::make($child);
                $category->addChild($childCategory);
            }
        }

        return $category;
    }
}
