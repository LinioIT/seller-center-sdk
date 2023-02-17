<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\ProductData;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class ProductManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var Products
     */
    protected $products;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

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
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));
        $result = $sdkClient->products()->getAllProducts();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsCreatedAfterADateTime(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $createdAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        $result = $sdkClient->products()->getProductsCreatedAfter($createdAfter);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsCreatedBeforeADateTime(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $createdBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->products()->getProductsCreatedBefore($createdBefore);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsUpdatedAfterADateTime(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $updatedAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->products()->getProductsUpdatedAfter($updatedAfter);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsUpdatedBeforeADateTime(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $updatedBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->products()->getProductsUpdatedBefore($updatedBefore);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsSearchedByValue(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

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
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $result = $sdkClient->products()->filterProducts($filters);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItReturnsACollectionOfProductsBySkuSellerList(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $skuSellerList = ['jasku-10001', 'jasku-10002'];

        $result = $sdkClient->products()->getProductsBySellerSku($skuSellerList);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
    }

    public function testItThrowsExceptionWithANullSkuSellerList(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

        $sdkClient->products()->getProductsBySellerSku([]);
    }

    public function testItReturnsACollectionOfProductsFromParameters(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Product/ProductsResponse.xml'));

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

    /**
     * @dataProvider productActions
     */
    public function testItReturnsAFeedResponseFromAProductActionRequest(string $action): void
    {
        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponse.xml'),
            ucfirst($action)
        );

        $sdkClient = $this->getSdkClient($body);

        $result = $sdkClient->products()->{$action}($this->products);

        $this->assertIsArray($this->products->all());
        $this->assertContainsOnlyInstancesOf(
            Product::class,
            $this->products->all()
        );
        $this->assertInstanceOf(FeedResponse::class, $result);
    }

    /**
     * @dataProvider validImageRequests
     */
    public function testItReturnsFeedResponseFromAnAddImageRequest(array $images): void
    {
        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponse.xml'),
            'Image'
        );

        $sdkClient = $this->getSdkClient($body);

        $result = $sdkClient->products()->addImage($images);

        $this->assertIsArray($this->products->all());
        $this->assertContainsOnlyInstancesOf(Product::class, $this->products->all());
        $this->assertInstanceOf(FeedResponse::class, $result);
    }

    /**
     * @dataProvider productActions
     */
    public function testItReturnsErrorResponseException(string $action): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponseError.xml'),
            ucfirst($action),
            'Sender',
            125,
            'E0125: Test Error'
        );

        $sdkClient = $this->getSdkClient($body, null, 400);

        $sdkClient->products()->productCreate($this->products);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenProductCreateSuccessResponse(bool $debug): void
    {
        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponse.xml'),
            'productCreate'
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->products()->productCreate($this->products, $debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenProductUpdateSuccessResponse(bool $debug): void
    {
        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponse.xml'),
            'productUpdate'
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->products()->productUpdate($this->products, $debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenProductRemoveSuccessResponse(bool $debug): void
    {
        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponse.xml'),
            'productRemove'
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->products()->productRemove($this->products, $debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenAddImageSuccessResponse(bool $debug): void
    {
        $body = sprintf(
            $this->getSchema('Feed/ProductActionFeedResponse.xml'),
            'Image'
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->products()->addImage(
            ['testSku' => [
                'http://static.somecdn.com/moneyshot.jpeg',
                'http://static.somecdn.com/front.jpeg',
                'http://static.somecdn.com/rear.jpeg',
            ],
            ],
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProducts(
            new Parameters(),
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetAllProductsSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getAllProducts(
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsCreatedAfterSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProductsCreatedAfter(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00'),
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsCreatedBeforeSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProductsCreatedBefore(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00'),
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsUpdatedAfterSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProductsUpdatedAfter(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00'),
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsUpdatedBeforeSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProductsUpdatedBefore(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00'),
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSearchProductsSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->searchProducts(
            'test-sku',
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenFilterProductsSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->filterProducts(
            'live',
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsBySellerSkuSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProductsBySellerSku(
            ['test-sku1', 'test-sku2'],
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetProductsFromParametersSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Product/ProductsResponse.xml'),
            $this->logger
        );

        $sdkClient->products()->getProductsFromParameters(
            null,
            null,
            null,
            'live',
            100,
            100,
            ['test-sku'],
            null,
            null,
            $debug
        );
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

    public function productActions(): array
    {
        return [
            ['productCreate'],
            ['productUpdate'],
            ['productRemove'],
        ];
    }

    public function primaryProduct(): Product
    {
        $sellerSku = '2145819109aaeu7';
        $name = 'Magic Product';
        $variation = '0';
        $primaryCategory = Category::fromName('Jeans');
        $description = 'This is a bold product.';
        $brand = Brand::fromName('Samsung');
        $productId = '123326998';
        $taxClass = 'IVA exento 0%';
        $productData = new ProductData('Nuevo', 0, 4, 5, 4);

        $product = Product::fromBasicData(
            $sellerSku,
            $name,
            $variation,
            $primaryCategory,
            $description,
            $brand,
            5999.00,
            $productId,
            $taxClass,
            $productData
        );

        $product->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        $categories = new Categories();
        $categories->add(Category::fromId($this->faker->randomNumber));
        $categories->add(Category::fromId($this->faker->randomNumber));
        $product->setCategories($categories);

        return $product;
    }

    public function secondProduct(): Product
    {
        $sellerSku = '2145887609aaeu7';
        $name = 'Rare Product';
        $variation = 'Large';
        $primaryCategory = Category::fromName('Camisas');
        $description = 'This is a bold product.';
        $brand = Brand::fromName('Motorola');
        $productId = '123326998';
        $taxClass = 'IVA exento 0%';
        $productData = new ProductData('Nuevo', 3, 0, 5, 4);

        $product = Product::fromBasicData(
            $sellerSku,
            $name,
            $variation,
            $primaryCategory,
            $description,
            $brand,
            9999.00,
            $productId,
            $taxClass,
            $productData
        );

        $product->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        return $product;
    }

    public function validImageRequests(): array
    {
        $randomString = $this->getFaker()->word();
        $randomNumberToString = (string) $this->getFaker()->randomNumber();

        return [
            [
                [
                    $randomString => [
                        'http://static.somecdn.com/moneyshot.jpeg',
                        'http://static.somecdn.com/front.jpeg',
                        'http://static.somecdn.com/rear.jpeg',
                    ],
                ],
            ],
            [
                [
                    $randomNumberToString => [
                        'http://static.somecdn.com/moneyshot.jpeg',
                        'http://static.somecdn.com/front.jpeg',
                        'http://static.somecdn.com/rear.jpeg',
                    ],
                ],
            ],
        ];
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}
