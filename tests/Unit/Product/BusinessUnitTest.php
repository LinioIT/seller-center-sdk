<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Product\BusinessUnitFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use SimpleXMLElement;

class BusinessUnitTest extends LinioTestCase
{
    /**
     * @var string|null
     */
    protected $businessUnit = 'Falabella';

    /**
     * @var string
     */
    protected $operatorCode = 'facl';

    /**
     * @var float
     */
    protected $price = 12990.00;

    /**
     * @var float|null
     */
    protected $specialPrice = 12000.00;

    /**
     * @var DateTimeInterface|null
     */
    protected $specialFromDate;

    /**
     * @var DateTimeInterface|null
     */
    protected $specialToDate;

    /**
     * @var int
     */
    protected $stock = 4;

    /**
     * @var string
     */
    protected $status = 'active';

    /**
     * @var int
     */
    protected $isPublished = 1;

    public function testItCreatesABusinessUnitWithMandatoryParameters(): void
    {
        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished
        );

        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), $this->operatorCode);
        $this->assertEquals($businessUnit->getPrice(), $this->price);
        $this->assertEquals($businessUnit->getStock(), $this->stock);
        $this->assertEquals($businessUnit->getStatus(), $this->status);
        $this->assertEquals($businessUnit->getIsPublished(), $this->isPublished);
        $this->assertEquals($businessUnit->getSaleStartDateString(), null);
        $this->assertEquals($businessUnit->getSaleEndDateString(), null);
    }

    public function testItCreatesABusinessUnitWithMandatoryAndOptionalParameters(): void
    {
        $this->specialFromDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-10T14:54:23+00:00');
        $this->specialToDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-20T14:54:23+00:00');

        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished,
            'Falabella',
            12000.00,
            $this->specialFromDate,
            $this->specialToDate
        );

        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), $this->operatorCode);
        $this->assertEquals($businessUnit->getPrice(), $this->price);
        $this->assertEquals($businessUnit->getStock(), $this->stock);
        $this->assertEquals($businessUnit->getStatus(), $this->status);
        $this->assertEquals($businessUnit->getIsPublished(), $this->isPublished);
        $this->assertEquals($businessUnit->getBusinessUnit(), $this->businessUnit);
        $this->assertEquals($businessUnit->getSalePrice(), $this->specialPrice);
        $this->assertEquals($businessUnit->getSaleStartDate(), $this->specialFromDate);
        $this->assertEquals($businessUnit->getSaleEndDate(), $this->specialToDate);
        $this->assertEquals($businessUnit->getSaleStartDateString(), $this->specialFromDate->format('Y-m-d H:i:s'));
        $this->assertEquals($businessUnit->getSaleEndDateString(), $this->specialToDate->format('Y-m-d H:i:s'));
    }

    public function testItCreatesAProductFromAnXml(): void
    {
        $this->specialFromDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-10T14:54:23+00:00');
        $this->specialToDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-20T14:54:23+00:00');
        $xml = sprintf(
            $this->getSchema('Product/BusinessUnit.xml'),
            $this->businessUnit,
            $this->operatorCode,
            $this->price,
            $this->specialPrice,
            $this->specialFromDate->format('Y-m-d H:i:s'),
            $this->specialToDate->format('Y-m-d H:i:s'),
            $this->stock,
            $this->status,
            $this->isPublished
        );

        $businessUnit = BusinessUnitFactory::make(new SimpleXMLElement($xml));

        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), $this->operatorCode);
        $this->assertEquals($businessUnit->getPrice(), $this->price);
        $this->assertEquals($businessUnit->getStock(), $this->stock);
        $this->assertEquals($businessUnit->getStatus(), $this->status);
        $this->assertEquals($businessUnit->getIsPublished(), $this->isPublished);
        $this->assertEquals($businessUnit->getBusinessUnit(), $this->businessUnit);
        $this->assertEquals($businessUnit->getSalePrice(), $this->specialPrice);
        $this->assertEquals($businessUnit->getSaleStartDate(), $this->specialFromDate);
        $this->assertEquals($businessUnit->getSaleEndDate(), $this->specialToDate);
        $this->assertEquals($businessUnit->getSaleStartDateString(), $this->specialFromDate->format('Y-m-d H:i:s'));
        $this->assertEquals($businessUnit->getSaleEndDateString(), $this->specialToDate->format('Y-m-d H:i:s'));
    }

    public function testItThrowsExceptionWhenOperatorCodeIsIncorrect(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter OperatorCode is invalid.');

        new BusinessUnit(
            'faco',
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished
        );
    }

    public function testItThrowsExceptionWhenPriceIsIncorrect(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter Price is invalid.');

        new BusinessUnit(
            $this->operatorCode,
            -1,
            $this->stock,
            $this->status,
            $this->isPublished
        );
    }

    public function testItThrowsExceptionWhenStockIsIncorrect(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter Stock is invalid.');

        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            -1,
            $this->status,
            $this->isPublished
        );
    }

    public function testItThrowsExceptionWhenStatusIsIncorrect(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter Status is invalid.');

        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            'Unavailable',
            $this->isPublished
        );
    }

    public function testItThrowsExceptionWhenBusinessUnitIsIncorrect(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter BusinessUnit is invalid.');

        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished,
            'Another Ecommerce'
        );
    }

    public function testItThrowsExceptionWhenSpecialPriceIsIncorrect(): void
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('The parameter SpecialPrice is invalid.');

        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished,
            'Falabella',
            -1000
        );
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $businessUnit = new BusinessUnit(
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished
        );

        $expectedJson = sprintf(
            '{"businessUnit":"","operatorCode":"%s","price":%f,"specialPrice":"","specialFromDate":"","specialToDate":"","stock":%d,"status":"%s","isPublished":%d}',
            $this->operatorCode,
            $this->price,
            $this->stock,
            $this->status,
            $this->isPublished
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($businessUnit));
    }

    public function testItThrowsAExceptionWithoutAOperatorCodeInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a BusinessUnit. The property OperatorCode should exist');

        BusinessUnitFactory::make(new SimpleXMLElement($this->getSchema('Product/BusinessUnitWithoutOperatorCode.xml')));
    }

    public function testItThrowsAExceptionWithoutAPriceInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a BusinessUnit. The property Price should exist');

        BusinessUnitFactory::make(new SimpleXMLElement($this->getSchema('Product/BusinessUnitWithoutPrice.xml')));
    }

    public function testItThrowsAExceptionWithoutAStockInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a BusinessUnit. The property Stock should exist');

        BusinessUnitFactory::make(new SimpleXMLElement($this->getSchema('Product/BusinessUnitWithoutStock.xml')));
    }

    public function testItThrowsAExceptionWithoutAStatusInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a BusinessUnit. The property Status should exist');

        BusinessUnitFactory::make(new SimpleXMLElement($this->getSchema('Product/BusinessUnitWithoutStatus.xml')));
    }

    public function testItThrowsAExceptionWithoutAIsPublishedInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a BusinessUnit. The property IsPublished should exist');

        BusinessUnitFactory::make(new SimpleXMLElement($this->getSchema('Product/BusinessUnitWithoutIsPublished.xml')));
    }
}
