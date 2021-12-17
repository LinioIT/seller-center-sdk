<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\SellerCenter\Factory\Xml\Product\GlobalProductsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Model\Product\BusinessUnits;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\ProductData;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Transformer\Product\ProductsTransformer;

class GlobalProductsTest extends LinioTestCase
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
            '2145819109aaeu7',
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
        $globalProduct->setParentSku('91230ej8913he89');

        $categories = new Categories();
        $categories->add(Category::fromId($this->faker->randomNumber));
        $categories->add(Category::fromId($this->faker->randomNumber));

        $globalProduct->setCategories($categories);
        $globalProduct->getImages()->addMany([
            new Image('http://static.somecdn.com/moneyshot.jpeg'),
            new Image('http://static.somecdn.com/front.jpeg'),
            new Image('http://static.somecdn.com/rear.jpeg'),
        ]);

        $this->products->add($globalProduct);

        $emptyProduct = new GlobalProduct();
        $emptyProduct->setSellerSku('2145819109aaeu7e');
        $this->products->add($emptyProduct);
    }

    public function testFindsAndReturnTheProductBySellerSku(): void
    {
        $response = $this->getResponseMock();
        $products = GlobalProductsFactory::make($response->Body);

        $sku = 'jasku-10003';

        $product = $products->findBySellerSku($sku);

        $this->assertInstanceOf(Products::class, $products);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, $products->all());
        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($sku, $product->getSellerSku());
    }

    public function testSearchesAndACollectionOfProductsByName(): void
    {
        $response = $this->getResponseMock();
        $products = GlobalProductsFactory::make($response->Body);

        $name = 'jasku-10003';

        $productsFilterByName = $products->searchByName($name);

        $this->assertInstanceOf(Products::class, $products);
        $this->assertContainsOnlyInstancesOf(GlobalProduct::class, []);
        $this->assertCount(1, $productsFilterByName);

        foreach ($productsFilterByName as $product) {
            $this->assertInstanceOf(GlobalProduct::class, $product);
            $this->assertEquals($name, $product->getName());
        }
    }

    public function testReturnsAnEmptyValueWhenNoProductWasFound(): void
    {
        $response = $this->getResponseMock();
        $products = GlobalProductsFactory::make($response->Body);

        $product = $products->findBySellerSku('non-existent-sku');

        $this->assertNull($product);
    }

    public function testTransformsAProductObjectIntoAnXmlRepresentation(): void
    {
        $xml = ProductsTransformer::asXml($this->products);

        $product = $this->products->findBySellerSku('2145819109aaeu7');

        $this->assertInstanceOf(Products::class, $this->products);
        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($product->getSellerSku(), $xml->Product->SellerSku);
        $this->assertEquals($product->getName(), $xml->Product->Name);
        $this->assertEquals($product->getVariation(), $xml->Product->Variation);
        $this->assertEquals($product->getPrimaryCategory()->getId(), (int) $xml->Product->PrimaryCategory);

        $secondaryCategories = $product->getCategories()->all();
        $categoriesIds = [];

        foreach ($secondaryCategories as $secondaryCategory) {
            $categoriesIds[] = $secondaryCategory->getId();
        }

        $this->assertContains(implode(',', $categoriesIds), $xml->Product->Categories);
        $this->assertEquals($product->getDescription(), $xml->Product->Description);
        $this->assertEquals($product->getBrand()->getName(), $xml->Product->Brand);
        $this->assertEquals($product->getProductId(), $xml->Product->ProductId);
        $this->assertEquals($product->getTaxClass(), $xml->Product->TaxClass);
        $this->assertInstanceOf(ProductData::class, $product->getProductData());
        $this->assertEquals($product->getProductData()->getAttribute('ConditionType'), $xml->Product->ProductData->ConditionType);
        $this->assertEquals($product->getProductData()->getAttribute('PackageHeight'), (float) $xml->Product->ProductData->PackageHeight);
        $this->assertEquals($product->getProductData()->getAttribute('PackageLength'), (float) $xml->Product->ProductData->PackageLength);
        $this->assertEquals($product->getProductData()->getAttribute('PackageWidth'), (float) $xml->Product->ProductData->PackageWidth);
        $this->assertEquals($product->getProductData()->getAttribute('PackageWeight'), (float) $xml->Product->ProductData->PackageWeight);
        $this->assertEquals($product->getParentSku(), $xml->Product->ParentSku);
    }

    public function testTransformsAProductSkuIntoAnXmlRepresentation(): void
    {
        $xml = ProductsTransformer::skusAsXml($this->products);

        $product = $this->products->findBySellerSku('2145819109aaeu7');

        $this->assertInstanceOf(Products::class, $this->products);
        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($product->getSellerSku(), $xml->Product->SellerSku);
    }

    public function testCreatesAProductAsXmlWithYourImages(): void
    {
        $xml = ProductsTransformer::imagesAsXml($this->products);

        $product = $this->products->findBySellerSku('2145819109aaeu7');

        $this->assertInstanceOf(Products::class, $this->products);
        $this->assertInstanceOf(GlobalProduct::class, $product);
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
        $products = GlobalProductsFactory::make($response->Body);

        $sku = 'jasku-10003';

        $product = $products->findBySellerSku($sku);

        $xmlProduct = $response->Body->Products->Product[2];

        $this->assertInstanceOf(Products::class, $products);
        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($product->getSellerSku(), $xmlProduct->SellerSku);
    }

    public function testCreatesAGlobalProductFromAXml(): void
    {
        $response = $this->getResponseMock();
        $products = GlobalProductsFactory::make($response->Body);

        $sku = 'jasku-10003';

        $product = $products->findBySellerSku($sku);

        $xmlProduct = $response->Body->Products->Product[2];

        $this->assertInstanceOf(Products::class, $products);
        $this->assertInstanceOf(GlobalProduct::class, $product);
        $this->assertEquals($product->getSellerSku(), $xmlProduct->SellerSku);
    }

    public function getResponseMock(string $xml = 'Product/GlobalProductsResponse.xml')
    {
        return simplexml_load_string($this->getSchema($xml));
    }
}
