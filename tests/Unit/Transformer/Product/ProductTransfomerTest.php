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

        $expectedXml = $this->getSchema('Product/ProductSpecialChars.xml');

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
     * @dataProvider productXMLForFashionAttrProvider
     */
    public function testItCreatesProductXMLFashionAttributes(GlobalProduct $product, string $expectedXml): void
    {
        $xml = new SimpleXMLElement('<Request/>');
        ProductTransformer::asXml($xml, $product);

        $this->assertXmlStringEqualsXmlString($expectedXml, $xml->asXML());
    }

    public function testItCreatesProductXMLWithOverridAttributes(): void
    {
        $businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            'facl',
            1299.00,
            100,
            'active'
        );
        $businessUnit->setOverrideAttributes(['SpecialPrice']);
        $businessUnits->add($businessUnit);

        $product = GlobalProduct::fromBasicData(
            '21458191097',
            'Magic Global Product',
            'XL',
            Category::fromId(123),
            'This is a bold product.',
            Brand::fromName('Samsung'),
            $businessUnits,
            '123326998',
            null,
            new ProductData('Nuevo', 1, 1, 1, 1)
        );

        $xml = new SimpleXMLElement('<Request/>');
        ProductTransformer::asXml($xml, $product);
        $expectedXml = $this->getSchema('Product/GlobalProductEmptySpecialPrice.xml');
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

        ProductTransformer::addAttributes($xml, $attributes, []);
        $children = $xml->children();

        $this->assertCount(1, $children);
        $this->assertEquals('Foo', $children[0]->getName());
        $this->assertEquals('Bar', (string) $children[0]);
    }

    public function testItAddsNullValuesIfExistInOverrideAttributesForCommonProducts(): void
    {
        $xml = new SimpleXMLElement('<Root />');

        $attributes = [
            'Main' => null,
            'EmptyCategories' => new Categories(),
            'Foo' => 'Bar',
        ];

        ProductTransformer::addAttributes($xml, $attributes, ['Main']);
        $children = $xml->children();

        $this->assertCount(2, $children);
        $this->assertEquals('Main', $children[0]->getName());
        $this->assertEmpty((string) $children[0]);
        $this->assertEquals('Foo', $children[1]->getName());
        $this->assertEquals('Bar', (string) $children[1]);
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
    public function productXMLForFashionAttrProvider(): array
    {
        return [
            'With Fashion attributes' => [
                'product' => $this->getGlobalProduct(true),
                'xmlExpected' => $this->getXmlExpected(true),
            ],
            'Without Fashion attributes' => [
                'product' => $this->getGlobalProduct(false),
                'xmlExpected' => $this->getXmlExpected(false),
            ],
        ];
    }

    public function getGlobalProduct(bool $hasFashionData): GlobalProduct
    {
        $businessUnits = new BusinessUnits();
        $businessUnit = new BusinessUnit(
            'facl',
            1299.00,
            100,
            'active'
        );
        $businessUnits->add($businessUnit);

        $product = GlobalProduct::fromBasicData(
            '21458191097',
            'Magic Global Product',
            $hasFashionData ? null : 'XL',
            Category::fromId(123),
            'This is a bold product.',
            Brand::fromName('Samsung'),
            $businessUnits,
            '123326998',
            null,
            new ProductData('Nuevo', 1, 1, 1, 1)
        );

        if ($hasFashionData) {
            $product->setColor('Beige');
            $product->setColorBasico('Beige');
            $product->setSize('L');
            $product->setTalla('XL');
        }

        return $product;
    }

    public function getXmlExpected(bool $hasFashionData): string
    {
        $schema = 'Product/GlobalProductFashionAttributes.xml';

        $xmlVariation = '<Variation>XL</Variation>';

        $xmlFashion = '<Color>Beige</Color>
        <ColorBasico>Beige</ColorBasico>
        <Size>L</Size>
        <Talla>XL</Talla>';

        return sprintf($this->getSchema($schema), $hasFashionData ? $xmlFashion : $xmlVariation);
    }
}
