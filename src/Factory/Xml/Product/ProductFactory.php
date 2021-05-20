<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\BaseProduct;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Product;
use SimpleXMLElement;

class ProductFactory
{
    public static function make(SimpleXMLElement $element): BaseProduct
    {
        self::ValidateBaseProductXmlStructure($element);

        if (!property_exists($element, 'BusinessUnits')) {
            $product = self::makeProduct($element);
        } else {
            $product = self::makeGlobalProduct($element);
        }

        return $product;
    }

    private static function makeProduct(SimpleXMLElement $element): Product
    {
        if (!property_exists($element, 'Price')) {
            throw new InvalidXmlStructureException('Product', 'Price');
        }

        $brand = Brand::fromName((string) $element->Brand);

        $primaryCategory = Category::fromName((string) $element->PrimaryCategory);

        $productData = ProductDataFactory::make($element->ProductData);

        $product = Product::fromBasicData(
            (string) $element->SellerSku,
            (string) $element->Name,
            (string) $element->Variation,
            $primaryCategory,
            (string) $element->Description,
            $brand,
            (float) $element->Price,
            (string) $element->ProductId,
            (string) $element->TaxClass,
            $productData
        );

        if (!empty($element->ShopSku)) {
            $product->setShopSku((string) $element->ShopSku);
        }

        if (!empty($element->ProductSin)) {
            $product->setProductSin((string) $element->ProductSin);
        }

        if (!empty($element->ParentSku)) {
            $product->setParentSku((string) $element->ParentSku);
        }

        if (!empty($element->Status)) {
            $product->setStatus((string) $element->Status);
        }

        if (!empty($element->Categories)) {
            $categories = CategoriesFactory::makeFromXmlString($element->Categories);
            $product->setCategories($categories);
        }

        if (!empty($element->SalePrice)) {
            $product->setSalePrice((float) $element->SalePrice);
        }

        if (!empty($element->SaleStartDate)) {
            $saleStartDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->SaleStartDate);

            if ($saleStartDate) {
                $product->setSaleStartDate($saleStartDate);
            }
        }

        if (!empty($element->SaleEndDate)) {
            $saleEndDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->SaleEndDate);

            if ($saleEndDate) {
                $product->setSaleEndDate($saleEndDate);
            }
        }

        if (!empty($element->Quantity)) {
            $product->setQuantity((int) $element->Quantity);
        }

        if (!empty($element->Available)) {
            $product->setAvailable((int) $element->Available);
        }

        if (!empty($element->Images)) {
            $images = ImagesFactory::make($element->Images);
            $product->attachImages($images);
        }

        if (!empty($element->MainImage)) {
            $image = new Image((string) $element->MainImage);
            $product->setMainImage($image);
        }

        return $product;
    }

    private static function makeGlobalProduct(SimpleXMLElement $element): GlobalProduct
    {
        if ($element->BusinessUnits->BusinessUnit->count() == 0) {
            throw new InvalidXmlStructureException('Product', 'BusinessUnits');
        }

        $businessUnits = BusinessUnitsFactory::make($element->BusinessUnits);

        $brand = Brand::fromName((string) $element->Brand);

        $primaryCategory = Category::fromName((string) $element->PrimaryCategory);

        $productData = ProductDataFactory::make($element->ProductData);

        $product = GlobalProduct::fromBasicData(
            (string) $element->SellerSku,
            (string) $element->Name,
            (string) $element->Variation,
            $primaryCategory,
            (string) $element->Description,
            $brand,
            $businessUnits,
            (string) $element->ProductId,
            (string) $element->TaxClass,
            $productData
        );

        if (!empty($element->ShopSku)) {
            $product->setShopSku((string) $element->ShopSku);
        }

        if (!empty($element->ProductSin)) {
            $product->setProductSin((string) $element->ProductSin);
        }

        if (!empty($element->ParentSku)) {
            $product->setParentSku((string) $element->ParentSku);
        }

        if (!empty($element->Categories)) {
            $categories = CategoriesFactory::makeFromXmlString($element->Categories);
            $product->setCategories($categories);
        }

        if (!empty($element->Images)) {
            $images = ImagesFactory::make($element->Images);
            $product->attachImages($images);
        }

        if (!empty($element->MainImage)) {
            $image = new Image((string) $element->MainImage);
            $product->setMainImage($image);
        }

        return $product;
    }

    private static function validateBaseProductXmlStructure(SimpleXMLElement $element): void
    {
        if (!property_exists($element, 'SellerSku')) {
            throw new InvalidXmlStructureException('Product', 'SellerSku');
        }

        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('Product', 'Name');
        }

        if (!property_exists($element, 'Variation')) {
            throw new InvalidXmlStructureException('Product', 'Variation');
        }

        if (!property_exists($element, 'PrimaryCategory')) {
            throw new InvalidXmlStructureException('Product', 'PrimaryCategory');
        }

        if (!property_exists($element, 'Description')) {
            throw new InvalidXmlStructureException('Product', 'Description');
        }

        if (!property_exists($element, 'Brand')) {
            throw new InvalidXmlStructureException('Product', 'Brand');
        }

        if (!property_exists($element, 'ProductId')) {
            throw new InvalidXmlStructureException('Product', 'ProductId');
        }

        if (!property_exists($element, 'TaxClass')) {
            throw new InvalidXmlStructureException('Product', 'TaxClass');
        }

        if (!property_exists($element, 'ProductData')) {
            throw new InvalidXmlStructureException('Product', 'ProductData');
        }
    }
}
