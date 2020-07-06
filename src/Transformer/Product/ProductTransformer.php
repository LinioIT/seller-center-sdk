<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Product;
use SimpleXMLElement;

class ProductTransformer
{
    public static function asXml(SimpleXMLElement &$xml, Product $product): void
    {
        $body = $xml->addChild('Product');
        self::addAttributes($body, $product->all());

        $productDataAttributes = $product->getProductData()->all();

        if (empty($productDataAttributes)) {
            return;
        }

        $productData = $body->addChild('ProductData');

        foreach ($productDataAttributes as $attributeKey => $attributeValue) {
            if (is_array($attributeValue)) {
                $attributeValue = implode(',', $attributeValue);
            }

            $productData->addChild((string) $attributeKey, htmlspecialchars((string) $attributeValue));
        }
    }

    private static function addAttributes(SimpleXMLElement $xml, array $attributes): void
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            if ($attributeValue === null) {
                continue;
            }

            $adaptedValue = self::attributeAsString($attributeValue);

            if ($adaptedValue === null) {
                continue;
            }

            $encodedValue = htmlspecialchars($adaptedValue);
            $xml->addChild($attributeName, $encodedValue);
        }
    }

    private static function attributeAsString($attribute): ?string
    {
        if (is_object($attribute)) {
            return self::attributeObjectAsString($attribute);
        }

        return (string) $attribute;
    }

    private static function attributeObjectAsString($attribute): ?string
    {
        $className = get_class($attribute);

        switch ($className) {
            case Category::class:
                return (string) $attribute->getId();
            case Categories::class:
                $categories = $attribute->all();

                if (empty($categories)) {
                    return null;
                }

                return self::getCategoriesAsString($attribute->all());
            case Brand::class:
                return $attribute->getName();
        }

        throw new InvalidDomainException($className);
    }

    /**
     * @param Category[] $categories
     */
    private static function getCategoriesAsString(array $categories): string
    {
        $categoriesIds = [];

        foreach ($categories as $category) {
            $categoriesIds[] = $category->getId();
        }

        return implode(',', $categoriesIds);
    }

    public static function skuAsXml(SimpleXMLElement &$xml, Product $product): void
    {
        $body = $xml->addChild('Product');
        $body->addChild('SellerSku', htmlspecialchars($product->getSellerSku()));
    }

    public static function imagesAsXml(SimpleXMLElement $xml, Product $product): void
    {
        $body = $xml->addChild('ProductImage');

        $body->addChild('SellerSku', htmlspecialchars($product->getSellerSku()));
        $images = $body->addChild('Images');

        foreach ($product->getImages()->all() as $image) {
            $images->addChild('Image', htmlspecialchars($image->getUrl()));
        }
    }
}
