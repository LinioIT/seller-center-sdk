<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Model\Product\BusinessUnits;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\ProductData;

class GlobalProductTest extends LinioTestCase
{
    protected $sellerSku = '2145819109aaeu7';
    protected $newSellerSku = null;
    protected $name = 'Magic Product';
    protected $variation = 'XL';
    protected $primaryCategory;
    protected $description = 'This is a bold product.';
    protected $brand;
    protected $price = 5999.00;
    protected $taxClass = 'IVA exento 0%';
    protected $productId = '123326998';
    protected $operatorCode = 'facl';
    protected $productData;
    protected $businessUnits;

    protected $shopSku = 'HA997TB1EVQQ2LCO-9273602';
    protected $productSin = '4K173432N2D5';
    protected $parentSku = '2145819188aaeu3';
    protected $status = 'inactive';
    protected $qcStatus = 'approved';
    protected $stock = 100;
    protected $categories;
    protected $salePrice = 4000.00;
    protected $saleStartDate;
    protected $saleEndDate;
    protected $mainImage;
    protected $images;

    protected $conditionType = 'Nuevo';
    protected $packageHeight = 3;
    protected $packageWidth = 0;
    protected $packageLength = 5;
    protected $packageWeight = 6;

    protected $product;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->getFaker();

        $this->brand = Brand::fromName('Samsung');

        $this->primaryCategory = Category::fromName('Celulares');

        $this->productData = new ProductData(
            $this->conditionType,
            $this->packageHeight,
            $this->packageWidth,
            $this->packageLength,
            $this->packageWeight
        );

        $this->businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            0
        );
        $this->businessUnits->add($businessUnit);

        $this->categories = new Categories();
        $this->categories->add(Category::fromId($this->faker->randomNumber));
        $this->categories->add(Category::fromId($this->faker->randomNumber));

        $this->saleStartDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2013-09-03T11:31:23+00:00');
        $this->saleEndDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2013-10-03T11:31:23+00:00');

        $this->mainImage = new Image($this->faker->imageUrl($width = 640, $height = 480));

        $this->images = new Images();
        $this->images->addMany([
            new Image($this->faker->imageUrl($width = 640, $height = 480)),
            new Image($this->faker->imageUrl($width = 640, $height = 480)),
            new Image($this->faker->imageUrl($width = 640, $height = 480)),
        ]);
    }

    public function testItCreatesAGlobalProductWithMandatoryParameters(): void
    {
        $product = GlobalProduct::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->businessUnits,
            $this->productId,
            $this->taxClass,
            $this->productData,
            $this->images
        );

        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($product->getSellerSku(), $this->sellerSku);
        $this->assertEquals($product->getName(), $this->name);
        $this->assertEquals($product->getVariation(), $this->variation);
        $this->assertEquals($product->getPrimaryCategory(), $this->primaryCategory);
        $this->assertEquals($product->getDescription(), $this->description);
        $this->assertEquals($product->getBrand(), $this->brand);
        $this->assertEquals($product->getProductId(), $this->productId);
        $this->assertEquals($product->getTaxClass(), $this->taxClass);
        $this->assertEquals($product->getProductData(), $this->productData);
        $this->assertEquals($product->getQcStatus(), null);
        $this->assertInstanceOf(Images::class, $product->getImages());
        $this->assertInstanceOf(BusinessUnits::class, $product->getBusinessUnits());
    }

    public function testItCreatesAGlobalProductWithMandatoryAndOptionalParameters(): void
    {
        $product = GlobalProduct::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->businessUnits,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $product->setShopSku($this->shopSku);
        $product->setProductSin($this->productSin);
        $product->setParentSku($this->parentSku);
        $product->setCategories($this->categories);
        $product->setMainImage($this->mainImage);
        $product->setQcStatus($this->qcStatus);
        $product->attachImages($this->images);

        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($product->getSellerSku(), $this->sellerSku);
        $this->assertEquals($product->getName(), $this->name);
        $this->assertEquals($product->getVariation(), $this->variation);
        $this->assertEquals($product->getPrimaryCategory(), $this->primaryCategory);
        $this->assertEquals($product->getDescription(), $this->description);
        $this->assertEquals($product->getBrand(), $this->brand);
        $this->assertEquals($product->getProductId(), $this->productId);
        $this->assertEquals($product->getTaxClass(), $this->taxClass);
        $this->assertEquals($product->getProductData(), $this->productData);
        $this->assertEquals($product->getShopSku(), $this->shopSku);
        $this->assertEquals($product->getProductSin(), $this->productSin);
        $this->assertEquals($product->getParentSku(), $this->parentSku);
        $this->assertEquals($product->getCategories(), $this->categories);
        $this->assertEquals($product->getMainImage(), $this->mainImage);
        $this->assertEquals($product->getImages(), $this->images);
        $this->assertEquals($product->getQcStatus(), $this->qcStatus);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $product = GlobalProduct::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->businessUnits,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $product->setShopSku($this->shopSku);
        $product->setProductSin($this->productSin);
        $product->setParentSku($this->parentSku);
        $product->setCategories($this->categories);
        $product->setMainImage($this->mainImage);
        $product->setQcStatus($this->qcStatus);
        $product->attachImages($this->images);

        $expectedJson = $expectedJson = Json::decode($this->getSchema('Product/GlobalProduct.json'));
        $expectedJson['categories'][0]['categoryId'] = $this->categories->all()[0]->getId();
        $expectedJson['categories'][1]['categoryId'] = $this->categories->all()[1]->getId();
        $expectedJson['mainImage']['url'] = $this->mainImage->getUrl();
        $expectedJson['images'][0]['url'] = $this->images->all()[0]->getUrl();
        $expectedJson['images'][1]['url'] = $this->images->all()[1]->getUrl();
        $expectedJson['images'][2]['url'] = $this->images->all()[2]->getUrl();

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($product));
    }
}
