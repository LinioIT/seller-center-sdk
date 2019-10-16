<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Shipment;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Shipment\ShipmentProvider;
use Linio\SellerCenter\Model\Shipment\ShipmentProviders;

class ShipmentProvidersTest extends LinioTestCase
{
    public function testItFindsShipmentProviderByName(): void
    {
        $faker = $this->getFaker();
        $name = $faker->name;

        $shipmentProviders = new ShipmentProviders();
        $shipmentProvider = new ShipmentProvider($name);
        $shipmentProviders->add($shipmentProvider);

        $shipmentProviderFound = $shipmentProviders->findByName($name);

        $this->assertNotNull($shipmentProviderFound);
        $this->assertSame($shipmentProvider, $shipmentProviderFound);
    }

    public function testItReturnsNullWhenNoShipmentProviderWasFound(): void
    {
        $faker = $this->getFaker();
        $name = $faker->name;

        $shipmentProviders = new ShipmentProviders();

        $shipmentProviderFound = $shipmentProviders->findByName($name);

        $this->assertNull($shipmentProviderFound);
    }
}
