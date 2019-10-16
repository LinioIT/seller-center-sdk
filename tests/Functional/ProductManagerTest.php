<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\ProductData;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;

class ProductManagerTest extends LinioTestCase
{
    use ClientHelper;

    protected $products;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->getFaker();

        $this->products = new Products();

        $this->products->add($this->primaryProduct());
        $this->products->add($this->secondProduct());
    }

    public function testItReturnsACollectionOfProducts(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->products()->getAllProducts();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsCreatedAfterADateTime(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $createdAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        $result = $sdkClient->products()->getProductsCreatedAfter($createdAfter);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsCreatedBeforeADateTime(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $createdBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->products()->getProductsCreatedBefore($createdBefore);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsUpdatedAfterADateTime(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $updatedAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->products()->getProductsUpdatedAfter($updatedAfter);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsUpdatedBeforeADateTime(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $updatedBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->products()->getProductsUpdatedBefore($updatedBefore);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsSearchedByValue(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $search = 'pil';

        $result = $sdkClient->products()->searchProducts($search);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    /**
     * @dataProvider filters
     */
    public function testItReturnsACollectionOfProductsFiltered(string $filters): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->products()->filterProducts($filters);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsBySkuSellerList(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $skuSellerList = ['jasku-10001', 'jasku-10002'];

        $result = $sdkClient->products()->getProductsBySellerSku($skuSellerList);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItThrowsExceptionWithANullSkuSellerList(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->products()->getProductsBySellerSku([]);
    }

    public function testItReturnsACollectionOfProductsFromParameters(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $createdBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $createdAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $updatedAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $updatedBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $filter = 'invalidFilter';
        $search = 'pil';
        $skuSellerList = ['jasku-10001', 'jasku-10002'];

        $result = $sdkClient->products()->getProductsFromParameters(
            $createdBefore,
            $createdAfter,
            $search,
            $filter,
            1,
            0,
            $skuSellerList,
            $updatedAfter,
            $updatedBefore
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsAFeedResponseFromAProductCreateRequest(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                        <Head>
                            <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                            <RequestAction>ProductCreate</RequestAction>
                            <ResponseType/>
                            <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                        </Head>
                        <Body/>
                    </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->products()->productCreate($this->products);

        $this->assertIsArray($this->products->all());
        $this->assertContainsOnlyInstancesOf(Product::class, $this->products->all());
        $this->assertInstanceOf(FeedResponse::class, $result);
    }

    public function testItReturnsAFeedResponseFromAProductUpdateRequest(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                        <Head>
                            <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                            <RequestAction>ProductUpdate</RequestAction>
                            <ResponseType/>
                            <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                        </Head>
                        <Body/>
                    </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdk = new SellerCenterSdk($configuration, $client);

        $result = $sdk->products()->productUpdate($this->products);

        $this->assertInstanceOf(FeedResponse::class, $result);
    }

    public function testItReturnsAFeedResponseFromAProductRemoveRequest(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                      <Head>
                        <RequestId>f8bf8d09-1647-4136-b405-03c44f228cf5</RequestId>
                        <RequestAction>ProductRemove</RequestAction>
                        <ResponseType/>
                        <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                      </Head>
                      <Body/>
                    </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdk = new SellerCenterSdk($configuration, $client);

        $result = $sdk->products()->productRemove($this->products);

        $this->assertInstanceOf(FeedResponse::class, $result);
    }

    public function testItReturnsFeedResponseFromAnAddImageRequest(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                        <Head>
                            <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                            <RequestAction>Image</RequestAction>
                            <ResponseType/>
                            <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                        </Head>
                        <Body/>
                    </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdk = new SellerCenterSdk($configuration, $client);

        $images = [
            '2145887609aaeu7' => [
                'http://static.somecdn.com/moneyshot.jpeg',
                'http://static.somecdn.com/front.jpeg',
                'http://static.somecdn.com/rear.jpeg',
            ],
        ];

        $result = $sdk->products()->addImage($images);

        $this->assertIsArray($this->products->all());
        $this->assertContainsOnlyInstancesOf(Product::class, $this->products->all());
        $this->assertInstanceOf(FeedResponse::class, $result);
    }

    public function testItReturnsErrorResponseException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
            <Head>
                <RequestAction>GetOrder</RequestAction>
                <ErrorType>Sender</ErrorType>
                <ErrorCode>125</ErrorCode>
                <ErrorMessage>E0125: Test Error</ErrorMessage>
            </Head>
            <Body/>
        </ErrorResponse>';

        $client = $this->createClientWithResponse($body, 400);

        $env = $this->getParameters();

        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdk = new SellerCenterSdk($configuration, $client);

        $sdk->products()->productCreate($this->products);
    }

    public function filters(): array
    {
        return [
            ['all'],
            ['live'],
            ['inactive'],
            ['deleted'],
            ['image-missing'],
            ['pending'],
            ['rejected'],
            ['sold-out'],
            [''],
            ['invalid-filter'],
        ];
    }

    public function getResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
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
                                        <Variation>0</Variation>
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
                                        <PrimaryCategory>Sillas Portabebes para Bicicletas</PrimaryCategory>
                                        <Categories>1232,32132</Categories>
                                        <ProductData>
                                             <ConditionType>Nuevo</ConditionType>
                                             <PackageWidth>5</PackageWidth>
                                             <PackageLength>5</PackageLength>
                                             <PackageHeight>5</PackageHeight>
                                             <PackageWeight>1</PackageWeight>
                                             <ShortDescription>1,2,3,4</ShortDescription>
                                        </ProductData>
                                   </Product>
                                   <Product>
                                        <SellerSku>jasku-10002</SellerSku>
                                        <ShopSku>NE32HE293ED9</ShopSku>
                                        <ProductSin>MDWIJ239DAD9H</ProductSin>
                                        <Variation>...</Variation>
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
                                        <PrimaryCategory>Andadores de seguridad</PrimaryCategory>
                                        <Categories>2713,78123</Categories>
                                        <ProductData>
                                             <ConditionType>Nuevo</ConditionType>
                                             <PackageWidth>5</PackageWidth>
                                             <PackageLength>5</PackageLength>
                                             <PackageHeight>5</PackageHeight>
                                             <PackageWeight>1</PackageWeight>
                                             <ShortDescription>1,2,3,4</ShortDescription>
                                        </ProductData>
                                   </Product>
                                   <Product>
                                        <SellerSku>jasku-10003</SellerSku>
                                        <ShopSku>MMIDJ20M10JDM10</ShopSku>
                                        <ProductSin>MWQI10923D109J</ProductSin>
                                        <Name>jasku-10003</Name>
                                        <Variation>...</Variation>
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
                                        <PrimaryCategory>Sillas Portabebes para Bicicletas</PrimaryCategory>
                                        <Categories>2819,23819</Categories>
                                        <ProductData>
                                             <ConditionType>Reacondicionado</ConditionType>
                                             <PackageWidth>5</PackageWidth>
                                             <PackageLength>5</PackageLength>
                                             <PackageHeight>0</PackageHeight>
                                             <PackageWeight>1</PackageWeight>
                                             <ShortDescription>1,2,3,4</ShortDescription>
                                        </ProductData>
                                   </Product>
                              </Products>
                         </Body>
                    </SuccessResponse>';
    }

    public function primaryProduct(): Product
    {
        $product = Product::fromBasicData(
            '2145819109aaeu7',
            'Magic Product',
            '0',
            Category::fromName('Jeans'),
            'This is a bold product.',
            Brand::fromName('Samsung'),
            5999.00,
            'IVA exento 0%',
            '123326998',
            new ProductData('Nuevo', 0, 4, 5, 4)
        );

        $product->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        $categories = new Categories();
        $categories->add(Category::fromId($this->faker->randomNumber));
        $categories->add(Category::fromId($this->faker->randomNumber));

        return $product;
    }

    public function secondProduct(): Product
    {
        $product = Product::fromBasicData(
            '2145887609aaeu7',
            'Rare Product',
            'Large',
            Category::fromName('Camisas'),
            'This is a bold product.',
            Brand::fromName('Motorola'),
            9999.00,
            'IVA exento 0%',
            '123810998',
            new ProductData('Nuevo', 3, 0, 5, 4)
        );

        $product->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        return $product;
    }
}
