<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Product\ProductDataFactory;
use Linio\SellerCenter\Model\Product\ProductData;
use PHPStan\Testing\TestCase;
use SimpleXMLElement;

class ProductDataTest extends TestCase
{
    protected $conditionType = 'Nuevo';
    protected $packageHeight = 3;
    protected $packageWidth = 0;
    protected $packageLength = 5;
    protected $packageWeight = 4;

    public function testItCreatesAProductDataWithMandatoryParameters(): void
    {
        $arrayWithMandatoryFields = [
            'ConditionType' => 'Nuevo',
            'PackageHeight' => 3,
            'PackageWidth' => 0,
            'PackageLength' => 5,
            'PackageWeight' => 4,
        ];

        $productData = new ProductData($this->conditionType, $this->packageHeight, $this->packageWidth, $this->packageLength, $this->packageWeight);

        $this->assertInstanceOf(ProductData::class, $productData);
        $this->assertEquals($productData->all(), $arrayWithMandatoryFields);
        $this->assertEquals($productData->getAttribute('ConditionType'), $this->conditionType);
        $this->assertEquals($productData->getAttribute('PackageHeight'), $this->packageHeight);
        $this->assertEquals($productData->getAttribute('PackageWidth'), $this->packageWidth);
        $this->assertEquals($productData->getAttribute('PackageLength'), $this->packageLength);
        $this->assertEquals($productData->getAttribute('PackageWeight'), $this->packageWeight);
    }

    public function testItCreatesAProductDataWithMandatoryAndOptionalParameters(): void
    {
        $arrayWithMandatoryAndOptionalFields = [
            'ConditionType' => 'Nuevo',
            'PackageHeight' => 3,
            'PackageWidth' => 0,
            'PackageLength' => 5,
            'PackageWeight' => 4,
            'Megapixels' => 490,
            'OpticalZoom' => 7,
            'SystemMemory' => 4,
            'NumberCpus' => 32,
            'Network' => 'This is network',
        ];

        $productData = new ProductData($this->conditionType, $this->packageHeight, $this->packageWidth, $this->packageLength, $this->packageWeight);

        $productData->add('Megapixels', 490);
        $productData->add('OpticalZoom', 7);
        $productData->add('SystemMemory', 4);
        $productData->add('NumberCpus', 32);
        $productData->add('Network', 'This is network');

        $this->assertInstanceOf(ProductData::class, $productData);
        $this->assertEquals($productData->all(), $arrayWithMandatoryAndOptionalFields);
    }

    public function testItReturnsNullInGetAttributeWhenThisParameterDoesNotExist(): void
    {
        $productData = new ProductData($this->conditionType, $this->packageHeight, $this->packageWidth, $this->packageLength, $this->packageWeight);

        $this->assertInstanceOf(ProductData::class, $productData);
        $this->assertEquals($productData->getAttribute('ConditionType'), $this->conditionType);
        $this->assertEquals($productData->getAttribute('PackageHeight'), $this->packageHeight);
        $this->assertEquals($productData->getAttribute('PackageWidth'), $this->packageWidth);
        $this->assertEquals($productData->getAttribute('PackageLength'), $this->packageLength);
        $this->assertEquals($productData->getAttribute('PackageWeight'), $this->packageWeight);
        $this->assertNull($productData->getAttribute('SupplierType'));
    }

    public function testItThrowsExceptionWhenConditionTypeIsNull(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter ConditionType is invalid.');

        new ProductData('', $this->packageHeight, $this->packageWidth, $this->packageLength, $this->packageWeight);
    }

    /**
     * @dataProvider  invalidPackageMeasures
     */
    public function testItThrowsExceptionWhenPackageHeightIsInvalid(float $invalidMeasure): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter PackageHeight is invalid.');

        new ProductData($this->conditionType, $invalidMeasure, $this->packageWidth, $this->packageLength, $this->packageWeight);
    }

    /**
     * @dataProvider  invalidPackageMeasures
     */
    public function testItThrowsExceptionWhenPackageWidthIsInvalid(float $invalidMeasure): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter PackageWidth is invalid.');

        new ProductData($this->conditionType, $this->packageHeight, $invalidMeasure, $this->packageLength, $this->packageWeight);
    }

    /**
     * @dataProvider  invalidPackageMeasures
     */
    public function testItThrowsExceptionWhenPackageLengthIsInvalid(float $invalidMeasure): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter PackageLength is invalid.');

        new ProductData($this->conditionType, $this->packageHeight, $this->packageWidth, $invalidMeasure, $this->packageWeight);
    }

    /**
     * @dataProvider  invalidPackageMeasures
     */
    public function testItThrowsExceptionWhenPackageWeightIsInvalid(float $invalidMeasure): void
    {
        $this->expectException(InvalidDomainException::class);

        $this->expectExceptionMessage('The parameter PackageWeight is invalid.');

        new ProductData($this->conditionType, $this->packageHeight, $this->packageWidth, $this->packageLength, $invalidMeasure);
    }

    public function testItThrowsAExceptionWithoutAConditionTypeInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a ProductData. The property ConditionType should exist');

        $xml = '<ProductData>
                  <PackageHeight>10.00</PackageHeight>
                  <PackageLength>15.00</PackageLength>
                  <PackageWidth>5.00</PackageWidth>
                  <PackageWeight>0.70</PackageWeight>
                </ProductData>';

        ProductDataFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAExceptionWithoutAPackageHeightInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a ProductData. The property PackageHeight should exist');

        $xml = '<ProductData>
                  <ConditionType>Nuevo</ConditionType>
                  <PackageLength>15.00</PackageLength>
                  <PackageWidth>5.00</PackageWidth>
                  <PackageWeight>0.70</PackageWeight>
                </ProductData>';

        ProductDataFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAExceptionWithoutAPackageLengthInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a ProductData. The property PackageLength should exist');

        $xml = '<ProductData>
                  <ConditionType>Nuevo</ConditionType>
                  <PackageHeight>10.00</PackageHeight>
                  <PackageWidth>5.00</PackageWidth>
                  <PackageWeight>0.70</PackageWeight>
                </ProductData>';

        ProductDataFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAExceptionWithoutAPackageWidthInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a ProductData. The property PackageWidth should exist');

        $xml = '<ProductData>
                  <ConditionType>Nuevo</ConditionType>
                  <PackageHeight>10.00</PackageHeight>
                  <PackageLength>15.00</PackageLength>
                  <PackageWeight>0.70</PackageWeight>
                </ProductData>';

        ProductDataFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAExceptionWithoutAPackageWeightInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a ProductData. The property PackageWeight should exist');

        $xml = '<ProductData>
                  <ConditionType>Nuevo</ConditionType>
                  <PackageHeight>10.00</PackageHeight>
                  <PackageLength>15.00</PackageLength>
                  <PackageWidth>5.00</PackageWidth>
                </ProductData>';

        ProductDataFactory::make(new SimpleXMLElement($xml));
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $conditionType = 'Nuevo';
        $packageHeight = 10;
        $packageLength = 10;
        $packageWidth = 10;
        $packageWeight = 10;

        $simpleXml = simplexml_load_string(sprintf('<ProductData>
                  <ConditionType>%s</ConditionType>
                  <PackageHeight>%d</PackageHeight>
                  <PackageLength>%d</PackageLength>
                  <PackageWidth>%d</PackageWidth>
                  <PackageWeight>%d</PackageWeight>
                </ProductData>', $conditionType, $packageHeight, $packageLength, $packageWidth, $packageWeight));

        $productData = ProductDataFactory::make($simpleXml);

        $expectedJson = sprintf('{"ConditionType": "%s", "PackageHeight": %d, "PackageLength": %d, "PackageWidth": %d, "PackageWeight": %d}', $conditionType, $packageHeight, $packageLength, $packageWidth, $packageWeight);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($productData));
    }

    public function invalidPackageMeasures(): array
    {
        return [
            [-1],
            [-0.1],
        ];
    }
}
