<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\SellerCenter\Factory\Xml\Product\BusinessUnitsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Model\Product\BusinessUnits;

class BusinessUnitsTest extends LinioTestCase
{
    /**
     * @var BusinessUnits
     */
    protected $businessUnits;

    public function setUp(): void
    {
        parent::setUp();

        $this->businessUnits = new BusinessUnits();

        $this->businessUnits->add(
            new BusinessUnit(
                'facl',
                1200.00,
                50,
                'active',
                1
            )
        );

        $this->businessUnits->add(
            new BusinessUnit(
                'fape',
                1200.00,
                50,
                'active',
                1,
                'Falabella'
            )
        );
    }

    public function testCreatesABusinessUnitsFromAXml(): void
    {
        $xml = simplexml_load_string(
            '<Product>
              <BusinessUnits>
                <BusinessUnit>
                  <BusinessUnit>Falabella</BusinessUnit>
                  <OperatorCode>facl</OperatorCode>
                  <Price>1500.00</Price>
                  <SpecialPrice>1200.00</SpecialPrice>
                  <SpecialFromDate>2020-12-01 00:00:00</SpecialFromDate>
                  <SpecialToDate>2020-12-30 00:00:00</SpecialToDate>
                  <Stock>15</Stock>
                  <Status>active</Status>
                  <IsPublished>0</IsPublished>
                </BusinessUnit>
                <BusinessUnit>
                  <BusinessUnit>Linio</BusinessUnit>
                  <OperatorCode>licl</OperatorCode>
                  <Price>1500.00</Price>
                  <SpecialPrice>1200.00</SpecialPrice>
                  <SpecialFromDate>2020-12-01 00:00:00</SpecialFromDate>
                  <SpecialToDate>2020-12-30 00:00:00</SpecialToDate>
                  <Stock>15</Stock>
                  <IsPublished>0</IsPublished>
                </BusinessUnit>
              </BusinessUnits>
            </Product>'
        );

        $businessUnits = BusinessUnitsFactory::make($xml);

        $operatorCode = 'facl';

        $businessUnit = $businessUnits->findByOperatorCode($operatorCode);

        $xmlBusinessUnit = $xml->BusinessUnits->BusinessUnit[0];

        $this->assertInstanceOf(BusinessUnits::class, $businessUnits);
        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($businessUnit->getOperatorCode(), (string) $xmlBusinessUnit->OperatorCode);
    }

    public function testItCreatesABusinessUnitsAndReturnsAnEmptyArray(): void
    {
        $businessUnits = new BusinessUnits();

        $this->assertInstanceOf(BusinessUnits::class, $businessUnits);
        $this->assertIsArray($businessUnits->all());
        $this->assertCount(0, $businessUnits->all());
    }

    public function testItCreatesABusinessUnitsAndReturnsOneBusinessUnit(): void
    {
        $businessUnit = new BusinessUnit(
            'facl',
            1200.00,
            50,
            'active',
            1
        );
        $businessUnits = new BusinessUnits();
        $businessUnits->add($businessUnit);

        $this->assertCount(1, $businessUnits->all());
        $this->assertInstanceOf(BusinessUnits::class, $businessUnits);
        $this->assertIsArray($businessUnits->all());
    }

    public function testFindsAndReturnTheBusinessUnitByOperatorCode(): void
    {
        $operatorCode = 'fape';

        $businessUnit = $this->businessUnits->findByOperatorCode($operatorCode);

        $this->assertContainsOnlyInstancesOf(BusinessUnit::class, $this->businessUnits->all());
        $this->assertInstanceOf(BusinessUnit::class, $businessUnit);
        $this->assertEquals($operatorCode, $businessUnit->getOperatorCode());
    }

    public function testFindsAndReturnTheBusinessUnitByBusinessUnit(): void
    {
        $businessUnitString = 'Falabella';
        $businessUnitsByBusinessUnit = $this->businessUnits->searchByBusinessUnit($businessUnitString);

        $this->assertContainsOnlyInstancesOf(BusinessUnit::class, $businessUnitsByBusinessUnit);
        $this->assertCount(1, $businessUnitsByBusinessUnit);

        foreach ($businessUnitsByBusinessUnit as $aBusinessUnit) {
            $this->assertInstanceOf(BusinessUnit::class, $aBusinessUnit);
            $this->assertEquals($businessUnitString, $aBusinessUnit->getBusinessUnit());
        }
    }

    public function testReturnsAnEmptyValueWhenNoBusinessUnitWasFound(): void
    {
        $businessUnitByOperatorCode = $this->businessUnits->findByOperatorCode('sope');
        $businessUnitByBusinessUnit = $this->businessUnits->searchByBusinessUnit('Sodimac');

        $this->assertNull($businessUnitByOperatorCode);
        $this->assertIsArray($businessUnitByBusinessUnit);
        $this->assertCount(0, $businessUnitByBusinessUnit);
    }
}
