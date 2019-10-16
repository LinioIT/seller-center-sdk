<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use SimpleXMLElement;

class CategoriesFactory
{
    public static function make(SimpleXMLElement $xml): Categories
    {
        if (empty($xml->Categories->Category)) {
            throw new InvalidXmlStructureException('Categories', 'Category');
        }

        $categories = new Categories();

        foreach ($xml->Categories->Category as $item) {
            if (!empty($item)) {
                $category = CategoryFactory::make($item);
                $categories->add($category);
            }
        }

        return $categories;
    }

    public static function makeFromXmlString(SimpleXMLElement $element): Categories
    {
        /**
         * separate the categories by comma, as long as the comma is not followed by a space
         * example: <Categories>Otros tv, audio y video,TV Internet,Smart TV</Categories>
         * example result: ["Otros tv, audio y video", "TV Internet", "Smart TV"].
         */
        $categoriesArray = preg_split("/,(?=[^\s])/", (string) $element);

        $categories = new Categories();

        if ($categoriesArray) {
            foreach ($categoriesArray as $item) {
                if (!empty($item)) {
                    $category = Category::fromName($item);
                    $categories->add($category);
                }
            }
        }

        return $categories;
    }
}
