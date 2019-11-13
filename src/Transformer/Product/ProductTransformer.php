<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Model\Product\Product;
use SimpleXMLElement;

class ProductTransformer
{
    public static function asXml(SimpleXMLElement &$xml, Product $product): void
    {
        $body = $xml->addChild('Product');

        $body->addChild('SellerSku', htmlspecialchars($product->getSellerSku()));
        $body->addChild('Name', htmlspecialchars($product->getName()));
        $body->addChild('Variation', htmlspecialchars($product->getVariation()));
        $body->addChild('Status', htmlspecialchars($product->getStatus()));
        $body->addChild('PrimaryCategory', (string) $product->getPrimaryCategory()->getId());

        $secondaryCategories = $product->getCategories()->all();
        $categoriesIds = [];

        foreach ($secondaryCategories as $secondaryCategory) {
            $categoriesIds[] = $secondaryCategory->getId();
        }

        $body->addChild('Categories', implode(',', $categoriesIds));
        $body->addChild('Description', htmlspecialchars($product->getDescription()));
        $body->addChild('Brand', htmlspecialchars($product->getBrand()->getName()));
        $body->addChild('Price', (string) $product->getPrice());
        $body->addChild('ProductId', htmlspecialchars($product->getProductId()));
        $body->addChild('TaxClass', htmlspecialchars((string) $product->getTaxClass()));
        $body->addChild('ParentSku', htmlspecialchars((string) $product->getParentSku()));
        $body->addChild('Quantity', (string) $product->getQuantity());
        $body->addChild('SalePrice', (string) $product->getSalePrice());
        $body->addChild('SaleStartDate', (string) $product->getSaleStartDateString());
        $body->addChild('SaleEndDate', (string) $product->getSaleEndDateString());

        $productData = $body->addChild('ProductData');
        $attributes = $product->getProductData()->all();

        foreach ($attributes as $attributeKey => $attributeValue) {
            if (is_array($attributeValue)) {
                $attributeValue = implode(',', $attributeValue);
            }

            $productData->addChild($attributeKey, htmlspecialchars((string) $attributeValue));
        }
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
