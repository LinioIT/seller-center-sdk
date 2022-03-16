<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use SimpleXMLElement;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;

class ProductTransformer
{
    public const OVERRIDE_ALLOWED_ATTRIBUTES = ['SalePrice', 'SaleStartDate', 'SaleEndDate'];
    public static function asXml(SimpleXMLElement &$xml, ProductInterface $product): void
    {
        $body = $xml->addChild('Product');

        $overrideStatus = self::getOverrideStatus($product);
        self::addAttributes($body, $product->all(), $overrideStatus);

        $productDataAttributes = $product->getProductData()->all();

        if ($product instanceof GlobalProduct) {
            $businessUnits = $product->getBusinessUnits();

            if (!empty($businessUnits)) {
                $businessUnitsElement = $body->addChild('BusinessUnits');
                foreach ($businessUnits->all() as $aBusinessUnit) {
                    $businessUnitElement = $businessUnitsElement->addChild('BusinessUnit');
                    $businessUnitAttributes = $aBusinessUnit->getAllAttributes();
                    foreach ($businessUnitAttributes as $attributeKey => $attributeValue) {
                        $businessUnitElement->addChild((string) $attributeKey, htmlspecialchars((string) $attributeValue));
                    }
                }
            }
        }

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

    /**
     * @param mixed[] $attributes
     */
    public static function addAttributes(SimpleXMLElement $xml, array $attributes, array $overrideAttributes): void
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            if(in_array($attributeName, $overrideAttributes)) {
                $xml->addChild(
                    $attributeName, 
                    $attributeValue ? htmlspecialchars((string) $attributeValue) : $attributeValue
                );
                continue;
            }

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

    /**
     * @return string[]
     */
    public static function getOverrideStatus(ProductInterface $product): array
    {
        if($product instanceof Product) {
            return $product->getOverrideAttributes();
        }

        return [];
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function attributeAsString($attribute): ?string
    {
        if (is_object($attribute)) {
            return self::attributeObjectAsString($attribute);
        }

        return (string) $attribute;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function attributeObjectAsString($attribute): ?string
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

    public static function skuAsXml(SimpleXMLElement &$xml, ProductInterface $product): void
    {
        $body = $xml->addChild('Product');
        $body->addChild('SellerSku', htmlspecialchars($product->getSellerSku()));
    }

    public static function imagesAsXml(SimpleXMLElement $xml, ProductInterface $product): void
    {
        $body = $xml->addChild('ProductImage');

        $body->addChild('SellerSku', htmlspecialchars($product->getSellerSku()));
        $images = $body->addChild('Images');

        foreach ($product->getImages()->all() as $image) {
            $images->addChild('Image', htmlspecialchars($image->getUrl()));
        }
    }
}
