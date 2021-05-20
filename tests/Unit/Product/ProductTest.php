<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Product\ProductFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\ProductData;
use SimpleXMLElement;

class ProductTest extends LinioTestCase
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
    protected $productData;

    protected $shopSku = 'HA997TB1EVQQ2LCO-9273602';
    protected $productSin = '4K173432N2D5';
    protected $parentSku = '2145819188aaeu3';
    protected $status = 'inactive';
    protected $categories;
    protected $salePrice = 4000.00;
    protected $saleStartDate;
    protected $saleEndDate;
    protected $quantity = 10;
    protected $available = 9;
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

        $this->productData->add('ShortDescription', '<ul><li>Prueba atributo T&B&nbsp;</li><li>Prueba atributo T&B&nbsp;</li><li>Prueba atributo T&B&nbsp;</li><li>Prueba atributo T&B&nbsp;</li></ul>');
        $this->productData->add('Color', 'Negro');
        $this->productData->add('ToysFeatures', 'BPA-Free');
        $this->productData->add('Gender', 'Niña');
        $this->productData->add('AgeGroup', '0 - 12 Meses');

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

    public function testItCreatesAProductWithMandatoryParameters(): void
    {
        $product = Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $this->sellerSku);
        $this->assertEquals($product->getName(), $this->name);
        $this->assertEquals($product->getVariation(), $this->variation);
        $this->assertEquals($product->getPrimaryCategory(), $this->primaryCategory);
        $this->assertEquals($product->getDescription(), $this->description);
        $this->assertEquals($product->getBrand(), $this->brand);
        $this->assertEquals($product->getPrice(), $this->price);
        $this->assertEquals($product->getProductId(), $this->productId);
        $this->assertEquals($product->getTaxClass(), $this->taxClass);
        $this->assertEquals($product->getProductData(), $this->productData);
        $this->assertInstanceOf(Images::class, $product->getImages());
    }

    public function testItCreatesAProductFromAnXml(): void
    {
        $xml = $this->createXmlStringForAProduct();

        $product = ProductFactory::make(new SimpleXMLElement($xml));

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $this->sellerSku);
        $this->assertEquals($product->getShopSku(), $this->shopSku);
        $this->assertEquals($product->getProductSin(), $this->productSin);
        $this->assertEquals($product->getName(), $this->name);
        $this->assertEquals($product->getVariation(), $this->variation);
        $this->assertEquals($product->getPrimaryCategory()->getName(), $this->primaryCategory->getName());
        $this->assertEquals($product->getDescription(), $this->description);
        $this->assertEquals($product->getBrand(), $this->brand);
        $this->assertEquals($product->getPrice(), $this->price);
        $this->assertEquals($product->getProductId(), $this->productId);
        $this->assertEquals($product->getTaxClass(), $this->taxClass);
        $this->assertEquals($product->getProductData(), $this->productData);
        $this->assertInstanceOf(Images::class, $product->getImages());
    }

    public function testItCreatesAProductWithMandatoryAndOptionalParameters(): void
    {
        $product = Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData,
            $this->images
        );

        $product->setShopSku($this->shopSku);
        $product->setProductSin($this->productSin);
        $product->setParentSku($this->parentSku);
        $product->setStatus($this->status);
        $product->setCategories($this->categories);
        $product->setSalePrice($this->salePrice);
        $product->setSaleStartDate($this->saleStartDate);
        $product->setSaleEndDate($this->saleEndDate);
        $product->setQuantity($this->quantity);
        $product->setAvailable($this->available);
        $product->setMainImage($this->mainImage);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $this->sellerSku);
        $this->assertEquals($product->getName(), $this->name);
        $this->assertEquals($product->getVariation(), $this->variation);
        $this->assertEquals($product->getPrimaryCategory(), $this->primaryCategory);
        $this->assertEquals($product->getDescription(), $this->description);
        $this->assertEquals($product->getBrand(), $this->brand);
        $this->assertEquals($product->getPrice(), $this->price);
        $this->assertEquals($product->getProductId(), $this->productId);
        $this->assertEquals($product->getTaxClass(), $this->taxClass);
        $this->assertEquals($product->getProductData(), $this->productData);
        $this->assertEquals($product->getShopSku(), $this->shopSku);
        $this->assertEquals($product->getProductSin(), $this->productSin);
        $this->assertEquals($product->getParentSku(), $this->parentSku);
        $this->assertEquals($product->getStatus(), $this->status);
        $this->assertEquals($product->getCategories(), $this->categories);
        $this->assertEquals($product->getSalePrice(), $this->salePrice);
        $this->assertEquals($product->getSaleStartDate(), $this->saleStartDate);
        $this->assertEquals($product->getSaleEndDate(), $this->saleEndDate);
        $this->assertEquals($product->getQuantity(), $this->quantity);
        $this->assertEquals($product->getAvailable(), $this->available);
        $this->assertEquals($product->getMainImage(), $this->mainImage);
        $this->assertEquals($product->getImages(), $this->images);
    }

    public function testItChangesAllMandatoryParameters(): void
    {
        $newName = 'ULTRA MAGIC PRODUCT';
        $newVariation = '0';
        $newPrimaryCategory = Category::fromName('Celulares');
        $newDescription = 'this is a amazing product.';
        $newBrand = Brand::fromName('Motorola');
        $newPrice = 7899.00;
        $newProductId = '982234112';
        $newTaxClass = 'IVA 12%';
        $newProductData = new ProductData('Reacondicionado', 6, 0, 3, 6);

        $product = Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $product->setName($newName);
        $product->setVariation($newVariation);
        $product->setPrimaryCategory($newPrimaryCategory);
        $product->setDescription($newDescription);
        $product->setBrand($newBrand);
        $product->setPrice($newPrice);
        $product->setProductId($newProductId);
        $product->setTaxClass($newTaxClass);
        $product->setProductData($newProductData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $this->sellerSku);
        $this->assertEquals($product->getName(), $newName);
        $this->assertEquals($product->getVariation(), $newVariation);
        $this->assertEquals($product->getPrimaryCategory(), $newPrimaryCategory);
        $this->assertEquals($product->getDescription(), $newDescription);
        $this->assertEquals($product->getBrand(), $newBrand);
        $this->assertEquals($product->getPrice(), $newPrice);
        $this->assertEquals($product->getProductId(), $newProductId);
        $this->assertEquals($product->getTaxClass(), $newTaxClass);
        $this->assertEquals($product->getProductData(), $newProductData);
    }

    public function testItMakesAProductFromXml(): void
    {
        $sXml = $this->createXmlStringForAProduct();

        $xml = new SimpleXMLElement($sXml);

        $product = ProductFactory::make($xml);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals((string) $xml->SellerSku, $product->getSellerSku());
        $this->assertEquals((string) $xml->Name, $product->getName());
        $this->assertEquals((string) $xml->Variation, $product->getVariation());
        $this->assertEquals((float) $xml->Price, $product->getPrice());
        $this->assertEquals((string) $xml->ProductId, $product->getProductId());
        $this->assertEquals((string) $xml->Description, $product->getDescription());
        $this->assertEquals((string) $xml->TaxClass, $product->getTaxClass());
        $this->assertEquals((string) $xml->Brand, $product->getBrand()->getName());
        $this->assertEquals((string) $xml->PrimaryCategory, $product->getPrimaryCategory()->getName());
        $this->assertInstanceOf(ProductData::class, $product->getProductData());
        $this->assertEquals($product->getProductData()->getAttribute('ConditionType'), (string) $xml->ProductData->ConditionType);
        $this->assertEquals($product->getProductData()->getAttribute('PackageHeight'), (float) $xml->ProductData->PackageHeight);
        $this->assertEquals($product->getProductData()->getAttribute('PackageLength'), (float) $xml->ProductData->PackageLength);
        $this->assertEquals($product->getProductData()->getAttribute('PackageWidth'), (float) $xml->ProductData->PackageWidth);
        $this->assertEquals($product->getProductData()->getAttribute('PackageWeight'), (float) $xml->ProductData->PackageWeight);
        $this->assertEquals($product->getProductData()->getAttribute('ShortDescription'), (string) $xml->ProductData->ShortDescription);

        $this->assertEquals((string) $xml->ShopSku, $product->getShopSku());
        $this->assertEquals((string) $xml->ProductSin, $product->getProductSin());
        $this->assertEquals((int) $xml->Quantity, $product->getQuantity());
        $this->assertEquals((int) $xml->Available, $product->getAvailable());
        $this->assertEquals((float) $xml->SalePrice, $product->getSalePrice());
        $this->assertEquals((string) $xml->SaleStartDate, $product->getSaleStartDate());
        $this->assertEquals((string) $xml->SaleEndDate, $product->getSaleEndDate());
        $this->assertNotEmpty($xml->SalePrice);
        $this->assertNotEmpty($xml->SaleStartDate);
        $this->assertNotEmpty($xml->SaleEndDate);
        $this->assertEquals((string) $xml->Status, $product->getStatus());
        $this->assertInstanceOf(Image::class, $product->getMainImage());
        $this->assertEquals((string) $xml->MainImage, $product->getMainImage()->getUrl());
        $this->assertInstanceOf(Images::class, $product->getImages());
        $this->assertContainsOnlyInstancesOf(Image::class, $product->getImages()->all());
        $this->assertCount(2, $product->getImages()->all());
    }

    /**
     * @dataProvider validAndInvalidPrices
     */
    public function testItReturnsAValidPriceOrNull(
        float $price,
        float $salePrice,
        float $expectedPrice,
        ?float $expectedSalePrice
    ): void {
        $product = Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $product->setPrice($price);
        $product->setSalePrice($salePrice);

        $this->assertEquals($expectedPrice, $product->getPrice());
        $this->assertEquals($expectedSalePrice, $product->getSalePrice());
    }

    /**
     * @dataProvider validAndInvalidQuantity
     */
    public function testItReturnsAValidOrInvalidQuantity(
        int $quantity,
        int $available,
        int $expectedQuantity,
        int $expectedAvailable
    ): void {
        $product = Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $product->setQuantity($quantity);
        $product->setAvailable($available);

        $this->assertEquals($expectedQuantity, $product->getQuantity());
        $this->assertEquals($expectedAvailable, $product->getAvailable());
    }

    public function testItSetNewSellerSku(): void
    {
        $product = Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );

        $product->setNewSellerSku('newSellerSku');
        $this->assertEquals('newSellerSku', $product->getNewSellerSku());
    }

    public function testItThrowsExceptionWhenSellerSkuIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter SellerSku should not be null.');

        Product::fromBasicData(
            '',
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );
    }

    public function testItThrowsExceptionWhenNameIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter Name should not be null.');

        Product::fromBasicData(
            $this->sellerSku,
            '',
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );
    }

    public function testItThrowsExceptionWhenDescriptionIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter Description should not be null.');

        Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            '',
            $this->brand,
            $this->price,
            $this->productId,
            $this->taxClass,
            $this->productData
        );
    }

    /**
     * @dataProvider invalidPrices
     */
    public function testItThrowsExceptionWhenPriceIsNull(float $invalidPrice): void
    {
        $this->expectException(InvalidDomainException::class);

        $this->expectExceptionMessage('The parameter Price is invalid.');

        Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $invalidPrice,
            $this->productId,
            $this->taxClass,
            $this->productData
        );
    }

    public function testItThrowsExceptionWhenProductIdIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter ProductId should not be null.');

        Product::fromBasicData(
            $this->sellerSku,
            $this->name,
            $this->variation,
            $this->primaryCategory,
            $this->description,
            $this->brand,
            $this->price,
            '',
            $this->taxClass,
            $this->productData
        );
    }

    /**
     * @dataProvider invalidXmlStructure
     */
    public function testItThrowsAExceptionWithoutAMandatoryPropertyInTheXml($property): void
    {
        $xmlString = $this->createXmlStringForAProduct();

        $xml = new SimpleXMLElement($xmlString);

        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a Product. The property %s should exist',
                $property
            )
        );

        unset($xml->{$property});

        ProductFactory::make($xml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $xml = $this->createXmlStringForAProduct();
        $simpleXml = simplexml_load_string($xml);

        $product = ProductFactory::make($simpleXml);

        $expectedJson = Json::decode($this->getSchema('Product/Product.json'));
        $expectedJson['status'] = $this->status;
        $expectedJson['taxClass'] = $this->taxClass;
        $expectedJson['productId'] = $this->productId;
        $expectedJson['brand']['name'] = $this->brand->getName();
        $expectedJson['description'] = $this->description;
        $expectedJson['primaryCategory']['name'] = $this->primaryCategory->getName();
        $expectedJson['variation'] = $this->variation;
        $expectedJson['name'] = $this->name;
        $expectedJson['productSin'] = $this->productSin;
        $expectedJson['shopSku'] = $this->shopSku;
        $expectedJson['sellerSku'] = $this->sellerSku;
        $expectedJson['price'] = $this->price;
        $expectedJson['productData']['ConditionType'] = $this->conditionType;
        $expectedJson['productData']['PackageHeight'] = $this->packageHeight;
        $expectedJson['productData']['PackageWidth'] = $this->packageWidth;
        $expectedJson['productData']['PackageLength'] = $this->packageLength;
        $expectedJson['productData']['PackageWeight'] = $this->packageWeight;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($product));
    }

    public function invalidPrices(): array
    {
        return [
            [0],
            [-0.1],
            [-1],
        ];
    }

    public function validAndInvalidPrices(): array
    {
        return [
            [-1, -1, $this->price, null],
            [-0.5, 1, $this->price, 1],
            [2.99, -0.5, 2.99, null],
            [2.99, 0, 2.99, null],
            [0, -1, $this->price, null],
            [0.5, 2, 0.5, null],
            [49.3, 34.2, 49.3, 34.2],
        ];
    }

    public function validAndInvalidQuantity(): array
    {
        return [
            [-1, -1, 0, 0],
            [0, 0, 0, 0],
            [3, 1, 3, 1],
            [5, 5, 5, 5],
            [3, 5, 3, 0],
        ];
    }

    public function invalidXmlStructure(): array
    {
        return [
            ['SellerSku'],
            ['Name'],
            ['Brand'],
            ['Description'],
            ['TaxClass'],
            ['Variation'],
            ['Price'],
            ['ProductId'],
            ['PrimaryCategory'],
            ['ProductData'],
        ];
    }

    public function createXmlStringForAProduct(): string
    {
        return sprintf(
            $this->getSchema('Product/Product.xml'),
            $this->sellerSku,
            $this->newSellerSku,
            $this->name,
            $this->shopSku,
            $this->productSin,
            $this->brand->getName(),
            $this->description,
            $this->taxClass,
            $this->variation,
            $this->price,
            $this->productId,
            $this->primaryCategory->getName(),
            $this->status,
            $this->conditionType,
            $this->packageHeight,
            $this->packageLength,
            $this->packageWidth,
            $this->packageWeight
        );
    }
}
