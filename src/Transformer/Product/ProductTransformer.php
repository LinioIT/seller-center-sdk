<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Product;
use SimpleXMLElement;

class ProductTransformer
{
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

            $variationAttributes = $product->getVariationAttributes() ? $product->getVariationAttributes()->all() : null;

            if (!empty($variationAttributes)) {
                self::addVariationAttributes($body, $variationAttributes);
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

            if (strtolower((string) $attributeKey) === 'description') {
                $value = self::removeTagsFromHtmlText((string) $attributeValue);
                $productData->addChild((string) $attributeKey, $value);
                continue;
            }

            $productData->addChild((string) $attributeKey, htmlspecialchars((string) $attributeValue));
        }
    }

    public static function removeTagsFromHtmlText(string $html): string
    {
        $decodedText = html_entity_decode($html);

        $regexFilters = [
            'doctype' => '/<!DOCTYPE html>/i',
            'meta' => '/<meta\b[^>]*\/?>/',
            'link' => '/<link\b[^>]*\/?>/',
            'head' => '/<head\b[^>]*>.*?<\/head>/i',
            'style' => '/<style\b[^>]*>.*?<\/style>/i',
            'script' => '/<script\b[^>]*>.*?<\/script>/i',
            'iframe' => '/<iframe\b[^>]*>.*?<\/iframe>/i',
            'section' => '/<\/?section\b[^>]*>/i',
            'a' => '/<a\b[^>]*>.*?<\/a>/i',
        ];

        $processedText = trim((string) preg_replace($regexFilters, '', $decodedText));
        $processedText = trim((string) preg_replace('/[\x{1F1E6}-\x{1F1FF}\x{1F300}-\x{1F5FF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{FE0F}\x{200D}\x{20E3}\x{1F3FB}-\x{1F3FF}]/u', '', $processedText));

        if (empty($processedText)) {
            return '';
        }

        return sprintf('<![CDATA[%s]]>', $processedText);
    }

    /**
     * @param mixed[] $attributes
     * @param string[] $overrideAttributes
     */
    public static function addAttributes(SimpleXMLElement $xml, array $attributes, array $overrideAttributes): void
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            if (in_array($attributeName, $overrideAttributes)) {
                $xml->addChild(
                    $attributeName,
                    $attributeValue ? htmlspecialchars((string) $attributeValue) : ''
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
     * @param mixed[] $variationAttributes
     */
    public static function addVariationAttributes(SimpleXMLElement $xml, array $variationAttributes): void
    {
        foreach ($variationAttributes as $attributeName => $attributeValue) {
            if ($attributeValue === null) {
                continue;
            }

            $encodedValue = htmlspecialchars($attributeValue);
            $xml->addChild($attributeName, $encodedValue);
        }
    }

    /**
     * @return string[]
     */
    public static function getOverrideStatus(ProductInterface $product): array
    {
        if ($product instanceof Product) {
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
