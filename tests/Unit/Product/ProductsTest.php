<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use DateTimeImmutable;
use Linio\SellerCenter\Factory\Xml\Product\ProductsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Model\Product\BusinessUnits;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\ProductData;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Transformer\Product\ProductsTransformer;

class ProductsTest extends LinioTestCase
{
    /**
     * @var Products
     */
    protected $products;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->getFaker();

        $this->products = new Products();
        $product = Product::fromBasicData(
            '2145819109aaeu7',
            'Magic Product',
            'XL',
            Category::fromId($this->faker->randomNumber),
            'This is a bold product.',
            Brand::fromName('Samsung'),
            5999.00,
            'IVA exento 0%',
            '123326998',
            new ProductData('Nuevo', 3, 0, 5, 4)
        );

        $businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            'facl',
            1299.00,
            100,
            'active',
            0
        );
        $businessUnits->add($businessUnit);

        $globalProduct = GlobalProduct::fromBasicData(
            '2145819109aaeu7g',
            'Magic Global Product ',
            'XL',
            Category::fromId($this->faker->randomNumber),
            'This is a bold product.',
            Brand::fromName('Samsung'),
            $businessUnits,
            'IVA exento 0%',
            '123326998',
            new ProductData('Nuevo', 3, 0, 5, 4)
        );
        $globalProduct->setQcStatus('pending');

        $categories = new Categories();
        $categories->add(Category::fromId($this->faker->randomNumber));
        $categories->add(Category::fromId($this->faker->randomNumber));

        $product->setCategories($categories);
        $product->setParentSku('91230ej8913he89');
        $product->setSalePrice(5888.00);
        $product->setSaleStartDate(DateTimeImmutable::createFromFormat(DATE_ATOM, '2013-09-03T11:31:23+00:00'));
        $product->setSaleEndDate(DateTimeImmutable::createFromFormat(DATE_ATOM, '2013-10-03T11:31:23+00:00'));
        $product->setQuantity(10);

        $product->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        $globalProduct->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        $emptyProduct = new Product();
        $emptyProduct->setSellerSku('2145819109aaeu7e');

        $this->products->add($product);
        $this->products->add($globalProduct);
        $this->products->add($emptyProduct);
    }

    public function testFindsAndReturnTheProductBySellerSku(): void
    {
        $response = $this->getResponseMock();
        $products = ProductsFactory::make($response->Body);

        $sku = 'jasku-10003';

        $product = $products->findBySellerSku($sku);

        $this->assertInstanceOf(Products::class, $products);
        $this->assertContainsOnlyInstancesOf(Product::class, $products->all());
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($sku, $product->getSellerSku());
    }

    public function testSearchesAndACollectionOfProductsByName(): void
    {
        $response = $this->getResponseMock();
        $products = ProductsFactory::make($response->Body);

        $name = 'jasku-10003';

        $productsFilterByName = $products->searchByName($name);

        $this->assertInstanceOf(Products::class, $products);
        $this->assertContainsOnlyInstancesOf(Product::class, []);
        $this->assertCount(1, $productsFilterByName);

        foreach ($productsFilterByName as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertEquals($name, $product->getName());
        }
    }

    public function testReturnsAnEmptyValueWhenNoProductWasFound(): void
    {
        $response = $this->getResponseMock();
        $products = ProductsFactory::make($response->Body);

        $product = $products->findBySellerSku('non-existent-sku');

        $this->assertNull($product);
    }

    public function testTransformsAProductObjectIntoAnXmlRepresentation(): void
    {
        $xml = ProductsTransformer::asXml($this->products);

        $product = $this->products->findBySellerSku('2145819109aaeu7');

        $this->assertInstanceOf(Products::class, $this->products);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $xml->Product->SellerSku);
        $this->assertEquals($product->getName(), $xml->Product->Name);
        $this->assertEquals($product->getVariation(), $xml->Product->Variation);
        $this->assertEquals($product->getStatus(), $xml->Product->Status);
        $this->assertEquals($product->getPrimaryCategory()->getId(), (int) $xml->Product->PrimaryCategory);

        $secondaryCategories = $product->getCategories()->all();
        $categoriesIds = [];

        foreach ($secondaryCategories as $secondaryCategory) {
            $categoriesIds[] = $secondaryCategory->getId();
        }

        $this->assertContains(implode(',', $categoriesIds), $xml->Product->Categories);
        $this->assertEquals($product->getDescription(), $xml->Product->Description);
        $this->assertEquals($product->getBrand()->getName(), $xml->Product->Brand);
        $this->assertEquals($product->getPrice(), (float) $xml->Product->Price);
        $this->assertEquals($product->getProductId(), $xml->Product->ProductId);
        $this->assertEquals($product->getTaxClass(), $xml->Product->TaxClass);
        $this->assertInstanceOf(ProductData::class, $product->getProductData());
        $this->assertEquals($product->getProductData()->getAttribute('ConditionType'), $xml->Product->ProductData->ConditionType);
        $this->assertEquals($product->getProductData()->getAttribute('PackageHeight'), (float) $xml->Product->ProductData->PackageHeight);
        $this->assertEquals($product->getProductData()->getAttribute('PackageLength'), (float) $xml->Product->ProductData->PackageLength);
        $this->assertEquals($product->getProductData()->getAttribute('PackageWidth'), (float) $xml->Product->ProductData->PackageWidth);
        $this->assertEquals($product->getProductData()->getAttribute('PackageWeight'), (float) $xml->Product->ProductData->PackageWeight);
        $this->assertEquals($product->getParentSku(), $xml->Product->ParentSku);
        $this->assertEquals($product->getSalePrice(), (float) $xml->Product->SalePrice);
        $this->assertEquals($product->getSaleStartDateString(), (string) $xml->Product->SaleStartDate);
        $this->assertEquals($product->getSaleEndDateString(), (string) $xml->Product->SaleEndDate);
        $this->assertEquals($product->getQuantity(), (int) $xml->Product->Quantity);
    }

    public function testTransformsAProductSkuIntoAnXmlRepresentation(): void
    {
        $xml = ProductsTransformer::skusAsXml($this->products);

        $product = $this->products->findBySellerSku('2145819109aaeu7');

        $this->assertInstanceOf(Products::class, $this->products);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $xml->Product->SellerSku);
    }

    public function testCreatesAProductAsXmlWithYourImages(): void
    {
        $xml = ProductsTransformer::imagesAsXml($this->products);

        $product = $this->products->findBySellerSku('2145819109aaeu7');

        $this->assertInstanceOf(Products::class, $this->products);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $xml->ProductImage->SellerSku);

        $images = $product->getImages()->all();

        $this->assertCount(3, $images);

        foreach ($images as $key => $image) {
            $this->assertContains($image->getUrl(), (string) $xml->ProductImage->Images->Image[$key]);
        }
    }

    public function testCreatesAProductFromAXml(): void
    {
        $response = $this->getResponseMock();
        $products = ProductsFactory::make($response->Body);

        $sku = 'jasku-10003';

        $product = $products->findBySellerSku($sku);

        $xmlProduct = $response->Body->Products->Product[2];

        $this->assertInstanceOf(Products::class, $products);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getSellerSku(), $xmlProduct->SellerSku);
    }

    public function getResponseMock($xml = null)
    {
        if (empty($xml)) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                              <RequestId/>
                              <RequestAction>GetProducts</RequestAction>
                              <ResponseType>Products</ResponseType>
                              <Timestamp>2019-02-05T11:39:11-0300</Timestamp>
                         </Head>
                         <Body>
                              <Products>
                                   <Product>
                                        <SellerSku>jasku-10001</SellerSku>
                                        <ShopSku>A89AS7A7S99</ShopSku>
                                        <ProductSin>SAU8SAY87AS</ProductSin>
                                        <Name>jasku-10001</Name>
                                        <Variation>...</Variation>
                                        <ParentSku>jasku-10001</ParentSku>
                                        <Quantity>1000</Quantity>
                                        <Available>1000</Available>
                                        <Price>22000.00</Price>
                                        <SalePrice>20000.00</SalePrice>
                                        <SaleStartDate>2019-01-23 00:00:00</SaleStartDate>
                                        <SaleEndDate>2019-02-25 00:00:00</SaleEndDate>
                                        <Status>active</Status>
                                        <ProductId>jasku-10001</ProductId>
                                        <Url/>
                                        <MainImage/>
                                        <Images>
                                          <Image>http://static.somesite.com/p/image1.jpg</Image>
                                          <Image>http://static.somesite.com/p/image2.jpg</Image>
                                          <Image>http://static.somesite.com/p/image3.jpg</Image>
                                        </Images>
                                        <Description>fdsfdsfds&lt;span&gt;&lt;/span&gt;</Description>
                                        <TaxClass>IVA 19%</TaxClass>
                                        <Brand>GENERIC</Brand>
                                        <PrimaryCategory>Bicicletas</PrimaryCategory>
                                        <Categories>Sillas Portabebes para Bicicletas</Categories>
                                        <ProductData>
                                             <ConditionType>Nuevo</ConditionType>
                                             <PackageWidth>5</PackageWidth>
                                             <PackageLength>0</PackageLength>
                                             <PackageHeight>5</PackageHeight>
                                             <PackageWeight>1</PackageWeight>
                                             <ShortDescription>1,2,3,4</ShortDescription>
                                        </ProductData>
                                   </Product>
                                   <Product>
                                        <SellerSku>jasku-10002</SellerSku>
                                        <ShopSku>NE32HE293ED9</ShopSku>
                                        <ProductSin>MDWIJ239DAD9H</ProductSin>
                                        <Variation>0</Variation>
                                        <ParentSku>jasku-10002</ParentSku>
                                        <Quantity>1000</Quantity>
                                        <Available>1000</Available>
                                        <Price>22000.00</Price>
                                        <SalePrice>20000.00</SalePrice>
                                        <SaleStartDate>2019-01-23 00:00:00</SaleStartDate>
                                        <SaleEndDate>2019-02-25 00:00:00</SaleEndDate>
                                        <Status>active</Status>
                                        <ProductId>jasku-10002</ProductId>
                                        <Url/>
                                        <MainImage/>
                                        <Images>
                                          <Image>http://static.somesite.com/p/image1.jpg</Image>
                                          <Image>http://static.somesite.com/p/image2.jpg</Image>
                                          <Image>http://static.somesite.com/p/image3.jpg</Image>
                                        </Images>
                                        <Description>ndjdhmq mamnk</Description>
                                        <TaxClass>IVA 19%</TaxClass>
                                        <Brand>GENERIC</Brand>
                                        <PrimaryCategory>Perros</PrimaryCategory>
                                        <Categories>Collares, arneses y correas para perros,Arneses para perros</Categories>
                                        <ProductData>
                                             <ConditionType>Nuevo</ConditionType>
                                             <PackageWidth>0</PackageWidth>
                                             <PackageLength>0</PackageLength>
                                             <PackageHeight>0</PackageHeight>
                                             <PackageWeight>0</PackageWeight>
                                             <ShortDescription>1,2,3,4</ShortDescription>
                                        </ProductData>
                                   </Product>
                                   <Product>
                                        <SellerSku>jasku-10003</SellerSku>
                                        <ShopSku>MMIDJ20M10JDM10</ShopSku>
                                        <ProductSin>MWQI10923D109J</ProductSin>
                                        <Name>jasku-10003</Name>
                                        <Variation>XL</Variation>
                                        <ParentSku>jasku-10003</ParentSku>
                                        <Quantity>1000</Quantity>
                                        <Available>1000</Available>
                                        <Price>22000.00</Price>
                                        <SalePrice>20000.00</SalePrice>
                                        <SaleStartDate>2019-01-23 00:00:00</SaleStartDate>
                                        <SaleEndDate>2019-02-25 00:00:00</SaleEndDate>
                                        <Status>active</Status>
                                        <ProductId>jasku-10003</ProductId>
                                        <Url/>
                                        <MainImage/>
                                        <Images>
                                          <Image>http://static.somesite.com/p/image1.jpg</Image>
                                          <Image>http://static.somesite.com/p/image2.jpg</Image>
                                          <Image>http://static.somesite.com/p/image3.jpg</Image>
                                        </Images>
                                        <Description>fdsfdsfds&lt;span&gt;&lt;/span&gt;</Description>
                                        <TaxClass>IVA 19%</TaxClass>
                                        <Brand>GENERIC</Brand>
                                        <PrimaryCategory>TVs</PrimaryCategory>
                                        <Categories>TV, Audio y Video,Smart TV</Categories>
                                        <ProductData>
                                             <ConditionType>Reacondicionado</ConditionType>
                                             <PackageWidth>5</PackageWidth>
                                             <PackageLength>5</PackageLength>
                                             <PackageHeight>5</PackageHeight>
                                             <PackageWeight>1</PackageWeight>
                                             <ShortDescription>1,2,3,4</ShortDescription>
                                        </ProductData>
                                   </Product>
                              </Products>
                         </Body>
                    </SuccessResponse>';
        }

        return simplexml_load_string($xml);
    }
}
