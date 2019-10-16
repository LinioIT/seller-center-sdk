<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Model\Category\CategoryAttributeOptions;
use SimpleXMLElement;

class CategoryAttributeOptionsFactory
{
    public static function make(SimpleXMLElement $xml): CategoryAttributeOptions
    {
        $attributeOptions = new CategoryAttributeOptions();

        foreach ($xml->Option as $item) {
            if (!empty($item)) {
                $option = CategoryAttributeOptionFactory::make($item);
                $attributeOptions->add($option);
            }
        }

        return $attributeOptions;
    }
}
