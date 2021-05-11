<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use PHPStan\Testing\TestCase;

class BusinessUnitTest extends TestCase
{
    protected $businessUnit = 'Falabella';
    protected $operatorCode = 'facl';
    protected $price = 12990.00;
    protected $specialPrice = 12000.00;
    protected $specialFromDate;
    protected $specialToDate;
    protected $stock = 4;
    protected $status = 'active';
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
}
