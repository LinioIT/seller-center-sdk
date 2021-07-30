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
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Model\Product\BusinessUnits;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\ProductData;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;

class GlobalProductManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var Products
     */
    protected $globalProducts;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var Configuration
     */
    protected $configuration;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->getFaker();

        $this->globalProducts = new Products();

        $this->globalProducts->add($this->primaryProduct(true));
        $this->globalProducts->add($this->secondProduct(true));

        $env = $this->getParameters();
        $this->configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
    }

    public function testItReturnsACollectionOfProducts(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $result = $sdkClient->globalProducts()->getAllProducts();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItReturnsACollectionOfProductsCreatedAfterADateTime(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );

        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $createdAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        $result = $sdkClient->globalProducts()->getProductsCreatedAfter($createdAfter);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItReturnsACollectionOfProductsCreatedBeforeADateTime(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );

        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $createdBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->globalProducts()->getProductsCreatedBefore($createdBefore);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItReturnsACollectionOfProductsUpdatedAfterADateTime(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $updatedAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->globalProducts()->getProductsUpdatedAfter($updatedAfter);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItReturnsACollectionOfProductsUpdatedBeforeADateTime(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $updatedBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');

        $result = $sdkClient->globalProducts()->getProductsUpdatedBefore($updatedBefore);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItReturnsACollectionOfProductsSearchedByValue(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $search = 'pil';

        $result = $sdkClient->globalProducts()->searchProducts($search);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    /**
     * @dataProvider filters
     */
    public function testItReturnsACollectionOfProductsFiltered(string $filters): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $result = $sdkClient->globalProducts()->filterProducts($filters);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItReturnsACollectionOfProductsBySkuSellerList(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $skuSellerList = ['jasku-10001', 'jasku-10002'];

        $result = $sdkClient->globalProducts()->getProductsBySellerSku($skuSellerList);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
    }

    public function testItThrowsExceptionWithANullSkuSellerList(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $sdkClient->globalProducts()->getProductsBySellerSku([]);
    }

    public function testItReturnsACollectionOfProductsFromParameters(): void
    {
        $client = $this->createClientWithResponse(
            $this->getSchema('Product/GlobalProductsResponse.xml')
        );
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $createdBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $createdAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $updatedAfter = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $updatedBefore = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-23 00:00:00');
        $filter = 'invalidFilter';
        $search = 'pil';
        $skuSellerList = ['jasku-10001', 'jasku-10002'];

        $result = $sdkClient->globalProducts()->getProductsFromParameters(
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
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $result);
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

        $client = $this->createClientWithResponse($body);
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $result = $sdkClient->globalProducts()->{$action}($this->globalProducts);

        $this->assertIsArray($this->globalProducts->all());
        $this->assertContainsOnlyInstancesOf(
            GlobalProduct::class,
            $this->globalProducts->all()
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

        $client = $this->createClientWithResponse($body);
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $result = $sdkClient->globalProducts()->addImage($images);

        $this->assertIsArray($this->globalProducts->all());
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $this->globalProducts->all());
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

        $client = $this->createClientWithResponse($body, 400);
        $sdkClient = new SellerCenterSdk($this->configuration, $client);

        $sdkClient->products()->productCreate($this->globalProducts);
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

    public function primaryProduct(): GlobalProduct
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

        $businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            'facl',
            5999.00,
            10,
            'active'
        );
        $businessUnits->add($businessUnit);

        $product = GlobalProduct::fromBasicData(
            $sellerSku,
            $name,
            $variation,
            $primaryCategory,
            $description,
            $brand,
            $businessUnits,
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

    public function secondProduct(): GlobalProduct
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

        $businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            'facl',
            5999.00,
            10,
            'active'
        );
        $businessUnits->add($businessUnit);

        $product = GlobalProduct::fromBasicData(
            $sellerSku,
            $name,
            $variation,
            $primaryCategory,
            $description,
            $brand,
            $businessUnits,
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
}
