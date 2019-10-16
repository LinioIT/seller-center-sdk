<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Brand\Brand;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidBrandIdException;
use Linio\SellerCenter\Exception\InvalidBrandNameException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Brand\BrandFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;
use SimpleXMLElement;

class BrandTest extends LinioTestCase
{
    public function testItReturnsTheValueWithEachAccessor(): void
    {
        $id = 1;
        $name = 'Commodore';
        $global = 'commodore';

        $simpleXml = simplexml_load_string(sprintf('<BrandFactory>
                            <BrandId>%d</BrandId>
                            <Name>%s</Name>
                            <GlobalIdentifier>%s</GlobalIdentifier>
                          </BrandFactory>', $id, $name, $global));

        $brand = BrandFactory::make($simpleXml);

        $this->assertEquals($brand->getBrandId(), $id);
        $this->assertEquals($brand->getName(), $name);
        $this->assertEquals($brand->getGlobalIdentifier(), $global);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $id = 1;
        $name = 'Commodore';
        $global = 'commodore';

        $simpleXml = simplexml_load_string(sprintf('<BrandFactory>
                            <BrandId>%d</BrandId>
                            <Name>%s</Name>
                            <GlobalIdentifier>%s</GlobalIdentifier>
                          </BrandFactory>', $id, $name, $global));

        $brand = BrandFactory::make($simpleXml);

        $expectedJson = sprintf('{"brandId": %d, "name": "%s", "globalIdentifier": "%s" }', $id, $name, $global);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($brand));
    }

    public function testItThrowsAnExceptionWithoutABrandId(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Brand. The property BrandId should exist.');

        $simpleXml = new SimpleXMLElement('<Brand>
                            <Name>Name</Name>
                            <GlobalIdentifier>GI</GlobalIdentifier>
                            </Brand>');
        BrandFactory::make($simpleXml);
    }

    public function testItThrowsAnExceptionWithoutAName(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Brand. The property Name should exist.');

        $simpleXml = new SimpleXMLElement('<Brand>
                            <BrandId>1</BrandId>
                            <GlobalIdentifier>GI</GlobalIdentifier></Brand>');
        BrandFactory::make($simpleXml);
    }

    public function testItThrowsAnExceptionWithoutAGlobalIdentifier(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Brand. The property GlobalIdentifier should exist.');

        $simpleXml = new SimpleXMLElement('<Brand>
                            <BrandId>1</BrandId>
                            <Name>Name</Name>
                            </Brand>');
        BrandFactory::make($simpleXml);
    }

    public function testItThrowsAnExceptionAIfTheBrandIdIsNull(): void
    {
        $this->expectException(InvalidBrandIdException::class);

        Brand::build(0, 'test-name', 'test-identifier');
    }

    public function testItThrowsAnExceptionIfTheNameIsNull(): void
    {
        $this->expectException(InvalidBrandNameException::class);

        Brand::build(1, '', 'test-identifier');
    }
}
