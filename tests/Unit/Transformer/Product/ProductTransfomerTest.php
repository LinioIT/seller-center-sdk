<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Model\Product\BusinessUnits;
use Linio\SellerCenter\Model\Product\ClothesData;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\ProductData;
use SimpleXMLElement;
use stdClass;

class ProductTransfomerTest extends LinioTestCase
{
    public function testItCreatesProductXMLWithSpecialChars(): void
    {
        $productData = new ProductData('Nuevo', 1, 1, 1, 1);
        $productData->add('ShortDescription', 'Short description & ampersand');
        $productData->add('MultiOption', ['Option one', 'Option two']);

        $product = Product::fromBasicData(
            'BLACK_BAG_TEST',
            'Black Leather bagskasd',
            'M',
            Category::fromId(7080),
            '<p>Womens black <b>leather & bag</b>, with ample space. Can be worn over the shoulder, or remove straps to carry in your hand.asdasd</p>',
            Brand::fromName('Apple'),
            30000,
            '123456783',
            'IVA 19%',
            $productData
        );

        $xml = new SimpleXMLElement('<Products/>');
        ProductTransformer::asXml($xml, $product);

        $expectedXml = '
            <Products>
                <Product>
                    <SellerSku>BLACK_BAG_TEST</SellerSku>
                    <Name>Black Leather bagskasd</Name>
                    <Variation>M</Variation>
                    <Status>active</Status>
                    <PrimaryCategory>7080</PrimaryCategory>
                    <Description>&lt;p&gt;Womens black &lt;b&gt;leather &amp; bag&lt;/b&gt;, with ample space. Can be worn over the shoulder, or remove straps to carry in your hand.asdasd&lt;/p&gt;</Description>
                    <Brand>Apple</Brand>
                    <Price>30000</Price>
                    <ProductId>123456783</ProductId>
                    <TaxClass>IVA 19%</TaxClass>
                    <Quantity>0</Quantity>
                    <ProductData>
                        <ConditionType>Nuevo</ConditionType>
                        <PackageHeight>1</PackageHeight>
                        <PackageWidth>1</PackageWidth>
                        <PackageLength>1</PackageLength>
                        <PackageWeight>1</PackageWeight>
                        <ShortDescription>Short description &amp; ampersand</ShortDescription>
                        <MultiOption>Option one,Option two</MultiOption>
                        </ProductData>
                    </Product>
                </Products>';

        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }

    public function testItCreatesProductImagesXMLWithSpecialChars(): void
    {
        $images = new Images();
        $image = new Image('https://fakeimg.pl/350x200/?text=World&font=lobster');
        $images->add($image);

        $product = Product::fromSku('SKU');
        $product->attachImages($images);

        $xml = new SimpleXMLElement('<Request/>');
        ProductTransformer::imagesAsXml($xml, $product);

        $expectedXml = '
            <Request>
                <ProductImage>
                    <SellerSku>SKU</SellerSku>
                    <Images>
                        <Image>https://fakeimg.pl/350x200/?text=World&amp;font=lobster</Image>
                    </Images>
                </ProductImage>
             </Request>';

        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }

    /**
     * @dataProvider productXMLForClothesAttrProvider
     */
    public function testItCreatesProductXMLClothesAttributes(GlobalProduct $product, string $expectedXml): void
    {
        $xml = new SimpleXMLElement('<Request/>');
        ProductTransformer::asXml($xml, $product);

        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }

    public function testItAddsAttributesIgnoringTheNullValues(): void
    {
        $xml = new SimpleXMLElement('<Root />');

        $attributes = [
            'Main' => null,
            'EmptyCategories' => new Categories(),
            'Foo' => 'Bar',
        ];

        ProductTransformer::addAttributes($xml, $attributes);
        $children = $xml->children();

        $this->assertCount(1, $children);
        $this->assertEquals('Foo', $children[0]->getName());
        $this->assertEquals('Bar', (string) $children[0]);
    }

    /**
     * @dataProvider transformedTypesToString
     */
    public function testItTransformsNotObjectTypeToString($value, $expectedString): void
    {
        $result = ProductTransformer::attributeAsString($value);
        $this->assertSame($expectedString, $result);
    }

    public function transformedTypesToString()
    {
        return [
            [1, '1'],
            [1.2, '1.2'],
            ['foo', 'foo'],
            [Category::fromId(111), '111'],
        ];
    }

    /**
     * @dataProvider transfomedObjectsProvider
     */
    public function testItTransformsAttributeObjectAsString($object, $expectedResult): void
    {
        if ($object instanceof stdClass) {
            $this->expectException(InvalidDomainException::class);
        }

        $result = ProductTransformer::attributeObjectAsString($object);
        $this->assertSame($expectedResult, $result);
    }

    public function transfomedObjectsProvider()
    {
        $categories = new Categories();
        $categories->add(Category::fromId(222));
        $categories->add(Category::fromId(333));

        return [
            [Category::fromId(111), '111'],
            [new Categories(), null],
            [$categories, '222,333'],
            [Brand::fromName('Linio'), 'Linio'],
            [new stdClass(), null],
        ];
    }

    /**
     * @return mixed[]
     */
    public function productXMLForClothesAttrProvider(): array
    {
        return [
            'With clothes attributes' => [
                'product' => $this->getGlobalProduct(true),
                'xmlExpected' => $this->getXmlExpected(true),
            ],
            'Without clothes attributes' => [
                'product' => $this->getGlobalProduct(false),
                'xmlExpected' => $this->getXmlExpected(false),
            ],
        ];
    }

    public function getGlobalProduct(bool $hasClothesData): GlobalProduct
    {
        $businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            'facl',
            1299.00,
            100,
            'active'
        );
        $businessUnits->add($businessUnit);

        return GlobalProduct::fromBasicData(
            '2145819109aaeu7',
            'Magic Global Product',
            'XL',
            Category::fromId(123),
            'This is a bold product.',
            Brand::fromName('Samsung'),
            $businessUnits,
            '123326998',
            null,
            new ProductData('Nuevo', 1, 1, 1, 1),
            null,
            null,
            $hasClothesData ? new ClothesData('Beige', 'Beige', 'L') : null
        );
    }

    public function getXmlExpected(bool $hasClothesData): string
    {
        $baseXml = '<?xml version="1.0"?>
        <Request>
            <Product>
                <SellerSku>2145819109aaeu7</SellerSku>
                <Name>Magic Global Product</Name>
                <Variation>XL</Variation>
                <PrimaryCategory>123</PrimaryCategory>
                <Description>This is a bold product.</Description>
                <Brand>Samsung</Brand>
                <ProductId>123326998</ProductId>
                %s
                <BusinessUnits>
                    <BusinessUnit>
                    <OperatorCode>facl</OperatorCode>
                    <Price>1299</Price>
                    <Stock>100</Stock>
                    <Status>active</Status>
                    </BusinessUnit>
                </BusinessUnits>
                <ProductData>
                    <ConditionType>Nuevo</ConditionType>
                    <PackageHeight>1</PackageHeight>
                    <PackageWidth>1</PackageWidth>
                    <PackageLength>1</PackageLength>
                    <PackageWeight>1</PackageWeight>
                </ProductData>   
            </Product>
        </Request>';

        $xmlClothes = '<Color>Beige</Color>
        <ColorBasico>Beige</ColorBasico>
        <Size>L</Size>';

        return sprintf($baseXml, $hasClothesData ? $xmlClothes : '');
    }
}
