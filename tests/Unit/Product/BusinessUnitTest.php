<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Contract\BusinessUnitOperatorCodes;
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
    protected $countryCode = 'cl';

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
            BusinessUnitOperatorCodes::COUNTRY_OPERATOR[$this->countryCode],
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

    public function testItCreatesABusinessUnitWithPriceParameters(): void
    {
        $businessUnit = new BusinessUnit(
            BusinessUnitOperatorCodes::COUNTRY_OPERATOR[$this->countryCode],
            $this->price,
            null,
            $this->status
        );

        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), $this->operatorCode);
        $this->assertEquals($businessUnit->getPrice(), $this->price);
        $this->assertNull($businessUnit->getStock());
        $this->assertEquals($businessUnit->getStatus(), $this->status);
        $this->assertNull($businessUnit->getIsPublished());
        $this->assertNull($businessUnit->getSaleStartDateString());
        $this->assertNull($businessUnit->getSaleEndDateString());
    }

    public function testItCreatesABusinessUnitWithStockParameters(): void
    {
        $businessUnit = new BusinessUnit(
            BusinessUnitOperatorCodes::COUNTRY_OPERATOR[$this->countryCode],
            null,
            $this->stock,
            $this->status
        );

        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), $this->operatorCode);
        $this->assertNull($businessUnit->getPrice());
        $this->assertEquals($businessUnit->getStock(), $this->stock);
        $this->assertEquals($businessUnit->getStatus(), $this->status);
        $this->assertNull($businessUnit->getIsPublished());
        $this->assertNull($businessUnit->getSaleStartDateString());
        $this->assertNull($businessUnit->getSaleEndDateString());
    }

    public function testItCreatesABusinessUnitWithZeroStockParameters(): void
    {
        $businessUnit = new BusinessUnit(
            BusinessUnitOperatorCodes::COUNTRY_OPERATOR[$this->countryCode],
            null,
            0,
            $this->status
        );

        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), $this->operatorCode);
        $this->assertNull($businessUnit->getPrice());
        $this->assertEquals($businessUnit->getStock(), 0);
        $this->assertEquals($businessUnit->getStatus(), $this->status);
        $this->assertNull($businessUnit->getIsPublished());
        $this->assertNull($businessUnit->getSaleStartDateString());
        $this->assertNull($businessUnit->getSaleEndDateString());
    }

    public function testItCreatesABusinessUnitWithMandatoryAndOptionalParameters(): void
    {
        $this->specialFromDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-10T14:54:23+00:00');
        $this->specialToDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-20T14:54:23+00:00');

        $businessUnit = new BusinessUnit(
            BusinessUnitOperatorCodes::COUNTRY_OPERATOR[$this->countryCode],
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
        $this->assertEquals($businessUnit->getAvailable(), $this->stock);
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

    /**
     * @dataProvider invalidParameters
     */
    public function testItThrowsExceptionWhenParameterIsIncorrect(
        $parameter,
        $operatorCode,
        $price,
        $stock,
        $status,
        $isPublished,
        $businessUnit,
        $specialPrice
    ): void {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage(sprintf('The parameter %s is invalid.', $parameter));

        $businessUnit = new BusinessUnit(
            $operatorCode,
            $price,
            $stock,
            $status,
            $isPublished,
            $businessUnit,
            $specialPrice
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

        $expectedJson = Json::decode($this->getSchema('Product/BusinessUnit.json'));

        $expectedJson['operatorCode'] = $this->operatorCode;
        $expectedJson['price'] = $this->price;
        $expectedJson['stock'] = $this->stock;
        $expectedJson['status'] = $this->status;
        $expectedJson['isPublished'] = $this->isPublished;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($businessUnit));
    }

    /**
     * @dataProvider invalidXmlStructure
     */
    public function testItThrowsAExceptionWithoutMandatoryParametersInTheXml(
        string $property
    ): void {
        $this->specialFromDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-10T14:54:23+00:00');
        $this->specialToDate = DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-5-20T14:54:23+00:00');
        $xmlString = sprintf(
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

        $xml = new SimpleXMLElement($xmlString);

        unset($xml->{$property});

        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a BusinessUnit. The property %s should exist',
                $property
            )
        );

        BusinessUnitFactory::make($xml);
    }

    public function invalidXmlStructure(): array
    {
        return [
            ['IsPublished'],
            ['Status'],
            ['Stock'],
            ['Price'],
            ['OperatorCode'],
        ];
    }

    public function invalidParameters(): array
    {
        return [
            ['SpecialPrice', $this->operatorCode, $this->price, $this->stock, $this->status, $this->isPublished, $this->businessUnit, -1000],
            ['Status', $this->operatorCode, $this->price, $this->stock, 'Unavailable', $this->isPublished, $this->businessUnit, $this->specialPrice],
            ['Stock', $this->operatorCode, $this->price, -1, $this->status, $this->isPublished, $this->businessUnit, $this->specialPrice],
            ['Price', $this->operatorCode, -1, $this->stock, $this->status, $this->isPublished, $this->businessUnit, $this->specialPrice],
            ['OperatorCode', 'sope', $this->price, $this->stock, $this->status, $this->isPublished, $this->businessUnit, $this->specialPrice],
        ];
    }
}
