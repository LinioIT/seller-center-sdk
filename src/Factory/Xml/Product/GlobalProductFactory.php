<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class GlobalProductFactory
{
    private const XML_MODEL = 'GlobalProduct';
    private const REQUIRED_FIELDS = [
        'SellerSku',
        'Name',
        'PrimaryCategory',
        'Description',
        'Brand',
        'ProductId',
        'TaxClass',
        'ProductData',
    ];

    public static function make(SimpleXMLElement $element): GlobalProduct
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

        if ($element->BusinessUnits->BusinessUnit->count() == 0) {
            throw new InvalidXmlStructureException('GlobalProduct', 'BusinessUnit');
        }

        $businessUnits = BusinessUnitsFactory::make($element->BusinessUnits);

        $brand = Brand::fromName((string) $element->Brand);

        $primaryCategory = Category::fromName((string) $element->PrimaryCategory);

        $productData = ProductDataFactory::make($element->ProductData);

        if (!empty($element->Images)) {
            $images = ImagesFactory::make($element->Images);
        }

        $product = GlobalProduct::fromBasicData(
            (string) $element->SellerSku,
            (string) $element->Name,
            (string) $element->Variation ?? null,
            $primaryCategory,
            (string) $element->Description,
            $brand,
            $businessUnits,
            (string) $element->ProductId,
            (string) $element->TaxClass,
            $productData,
            $images ?? null,
            (string) $element->QCStatus ?? null
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

        if (!empty($element->MainImage)) {
            $image = new Image((string) $element->MainImage);
            $product->setMainImage($image);
        }

        if (!empty($element->Url)) {
            $product->setUrl((string) $element->Url);
        }

        if (!empty($element->Color)) {
            $product->setColor((string) $element->Color);
        }

        if (!empty($element->ColorBasico)) {
            $product->setBasicColor((string) $element->ColorBasico);
        }

        if (!empty($element->Size)) {
            $product->setSize((string) $element->Size);
        }

        if (!empty($element->Talla)) {
            $product->setTalla((string) $element->Talla);
        }

        return $product;
    }
}
