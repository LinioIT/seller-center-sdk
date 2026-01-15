<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeInterface;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Warehouse\WarehouseFactory;
use Linio\SellerCenter\Model\Warehouse\Warehouse;
use Linio\SellerCenter\Model\Warehouse\WarehouseAddress;
use Linio\SellerCenter\Model\Warehouse\WorkingSchedules;

class WarehouseManagerTest extends LinioTestCase
{
    public const DEFAULT_LIMIT = 100;
    public const DEFAULT_OFFSET = 0;

    public function testItReturnsACollectionOfWarehouses(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Warehouse/WarehouseSuccessResponse.xml'));
        $result = $sdkClient->warehouses()->getWarehousesFromParameters(
            self::DEFAULT_LIMIT,
            self::DEFAULT_OFFSET,
            null,
            null,
            null,
            true
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Warehouse::class, $result);
    }

    public function testItReturnsACollectionOfWarehousesWithFilters(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Warehouse/WarehouseSuccessResponse.xml'));
        $result = $sdkClient->warehouses()->getWarehousesFromParameters(
            self::DEFAULT_LIMIT,
            self::DEFAULT_OFFSET,
            'only_shipments',
            true,
            true,
            true
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Warehouse::class, $result);
    }

    public function testItReturnsWarehouseByFacilityId(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Warehouse/WarehouseSuccessResponse.xml'));
        $result = $sdkClient->warehouses()->getWarehouseByFacilityId(
            'SD-SCE8788720B3799'
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Warehouse::class, $result);
    }

    public function testItReturnsWarehouseByWarehouseId(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Warehouse/WarehouseSuccessResponse.xml'));
        $result = $sdkClient->warehouses()->getWarehouseById(
            'FByS1D'
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Warehouse::class, $result);
    }

    public function testItReturnsAnExceptionForAddressEmpty(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Warehouse. The property address should exist');

        $error = '
            <Warehouse>
            <name>FBY Seller test 1 D3</name>
            <sellerWarehouseId>FByS1D</sellerWarehouseId>
            <facilityId>SD-SCE8788720B3799</facilityId>
            <warehouseType>only_shipments</warehouseType>
            <isFbf>true</isFbf>
            <isDefault>false</isDefault>
            </Warehouse>
        ';

        $xml = simplexml_load_string($error);
        WarehouseFactory::make($xml);
    }

    public function testItReturnsAnExceptionForFacilityIdEmpty(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Warehouse. The property facilityId should exist');

        $error = '
            <Warehouse>
            <name>FBY Seller test 1 D3</name>
            <sellerWarehouseId>FByS1D</sellerWarehouseId>
            <warehouseType>only_shipments</warehouseType>
            <facilityId/>
            <isFbf>true</isFbf>
            <isDefault>false</isDefault>
            </Warehouse>
        ';

        $xml = simplexml_load_string($error);
        WarehouseFactory::make($xml);
    }

    public function testItReturnsAWarehouse(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Warehouse/WarehouseSuccessResponse.xml'));
        $result = $sdkClient->warehouses()->getWarehouseByFacilityId(
            'SD-SCE8788720B3799'
        );

        /**
         * @var Warehouse $warehouse
         */
        foreach ($result as $warehouse) {
            $this->assertEquals('FBY Seller test 1 D3', $warehouse->getName());
            $this->assertEquals('FByS1D', $warehouse->getSellerWarehouseId());
            $this->assertEquals('only_shipments', $warehouse->getWarehouseType());
            $this->assertEquals('test@falabella.cl', $warehouse->getUpdatedBy());
            $this->assertTrue($warehouse->isPickupStore());
            $this->assertTrue($warehouse->isFbf());
            $this->assertFalse($warehouse->isDefault());
            $this->assertTrue($warehouse->isEnabled());
            $this->assertInstanceOf(WarehouseAddress::class, $warehouse->getWarehouseAddress());
            $this->assertInstanceOf(DateTimeInterface::class, $warehouse->getUpdatedAt());
            $this->assertInstanceOf(DateTimeInterface::class, $warehouse->getUpdatedAt());
            $this->assertNull($warehouse->getNodeId());
            $this->assertFalse($warehouse->isAvailableZone());
            $this->assertInstanceOf(WorkingSchedules::class, $warehouse->getWorkingSchedule());
            $this->assertInstanceOf(DateTimeInterface::class, $warehouse->getZoneUpdatedAt());
        }

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Warehouse::class, $result);
    }
}
