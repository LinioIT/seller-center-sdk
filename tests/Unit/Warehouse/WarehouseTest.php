<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Warehouse\Warehouse;
use Linio\SellerCenter\Model\Warehouse\WarehouseAddress;

class WarehouseTest extends LinioTestCase
{
    public function testItReturnsAJsonRepresentation(): void
    {
        $warehouse = new Warehouse(
            'testWareHouse26%',
            'fd',
            'SD-SCE8788B8D1F569',
            'only_shipments',
            true,
            false,
            false,
            true,
            new DateTimeImmutable('2025-11-24T11:12:59.747Z'),
            null,
            new WarehouseAddress(
                'Calle Mario Valdivia 18081',
                null,
                null,
                'Vitacura',
                'Santiago',
                'Metropolitana de Santiago',
                null,
                'CL',
                'Chile',
                null,
                null,
                null,
                null
            ),
            null,
            false,
            null,
            null
        );

        $this->assertSame($this->getMock(), Json::decode(Json::encode($warehouse)));
    }

    public function getMock(): array
    {
        return [
            'name' => 'testWareHouse26%',
            'sellerWarehouseId' => 'fd',
            'facilityId' => 'SD-SCE8788B8D1F569',
            'isFbf' => true,
            'isDefault' => false,
            'isPickupStore' => false,
            'isEnabled' => true,
            'updatedBy' => null,
            'warehouseAddress' => [
                'addressLine1' => 'Calle Mario Valdivia 18081',
                'addressLine2' => null,
                'addressLine3' => null,
                'municipal' => 'Vitacura',
                'city' => 'Santiago',
                'state' => 'Metropolitana de Santiago',
                'postCode' => null,
                'countryCode' => 'CL',
                'email' => null,
                'name' => null,
                'contacts' => null,
                'contactAddress2Code' => null,
                'country' => 'Chile',
            ],
            'nodeId' => null,
            'zoneAvailable' => false,
            'workingSchedule' => null,
            'updatedAt' => [
                'date' => '2025-11-24 11:12:59.747000',
                'timezone_type' => 2,
                'timezone' => 'Z',
            ],
            'zoneUpdatedAt' => null,
        ];
    }
}
