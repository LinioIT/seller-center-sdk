<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\ProductData;
use SimpleXMLElement;

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
                    <Categories/>
                    <Description>&lt;p&gt;Womens black &lt;b&gt;leather &amp; bag&lt;/b&gt;, with ample space. Can be worn over the shoulder, or remove straps to carry in your hand.asdasd&lt;/p&gt;</Description>
                    <Brand>Apple</Brand>
                    <Price>30000</Price>
                    <ProductId>123456783</ProductId>
                    <TaxClass>IVA 19%</TaxClass>
                    <ParentSku/>
                    <Quantity>0</Quantity>
                    <SalePrice/>
                    <SaleStartDate/>
                    <SaleEndDate/>
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
}
