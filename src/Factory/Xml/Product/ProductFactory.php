<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use DateTimeImmutable;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class ProductFactory
{
    private const XML_MODEL = 'Product';
    private const REQUIRED_FIELDS = [
        'SellerSku',
        'Name',
        'Variation',
        'PrimaryCategory',
        'Description',
        'Brand',
        'ProductId',
        'TaxClass',
        'ProductData',
        'Price',
    ];

    public static function make(SimpleXMLElement $element): Product
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

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

        if (!empty($element->Url)) {
            $product->setUrl((string) $element->Url);
        }

        return $product;
    }
}
