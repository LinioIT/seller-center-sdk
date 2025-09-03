<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\V2\Factory\Xml\Warehouse\WarehouseFactory;
use Linio\SellerCenter\V2\Model\Warehouse\Warehouse;
use PHPUnit\Framework\TestCase;

class WarehouseTest extends TestCase
{
    public function testItReturnsValidWarehouse(): void
    {
        $simplexml = simplexml_load_string(
            '<Warehouse>
                <FacilityId>123</FacilityId>
                <SellerWarehouseId>456</SellerWarehouseId>
            </Warehouse>'
        );
        $warehouse = WarehouseFactory::make($simplexml);
        $this->assertInstanceOf(Warehouse::class, $warehouse);

        $this->assertEquals('123', $warehouse->getFacilityId());
        $this->assertEquals('456', $warehouse->getSellerWarehouseId());
    }

    public function testItThrowsExceptionIfParameterIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Warehouse. The property FacilityId should exist.');

        $simplexml = simplexml_load_string(
            '<Warehouse>
                <SellerWarehouseId>456</SellerWarehouseId>
            </Warehouse>'
        );
        WarehouseFactory::make($simplexml);
    }
}
