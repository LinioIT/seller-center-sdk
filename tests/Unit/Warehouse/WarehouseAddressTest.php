<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use Linio\SellerCenter\Factory\Xml\Warehouse\WarehouseAddressFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Warehouse\WarehouseAddress;

class WarehouseAddressTest extends LinioTestCase
{
    public function testItReturnsValidWarehouseAddress(): void
    {
        $xml = '
        <address>
            <addressLine1>Choapa River, Coquimbo, Chile</addressLine1>
            <addressLine2>Local 32</addressLine2>
            <addressLine3/>
            <municipal>Illapel</municipal>
            <city>Choapa</city>
            <state>Coquimbo</state>
            <postcode>1930000</postcode>
            <countryCode>CL</countryCode>
            <email>test@falabella.cl</email>
            <name>nametest</name>
            <contacts/>
            <country>Chile</country>
            <contactAddress2Code>04201</contactAddress2Code>
        </address>
        ';

        $xml = simplexml_load_string($xml);
        $address = WarehouseAddressFactory::make($xml);

        $this->assertInstanceOf(WarehouseAddress::class, $address);

        $this->assertEquals('Choapa River, Coquimbo, Chile', $address->getAddressLine1());
        $this->assertEquals('Local 32', $address->getAddressLine2());
        $this->assertNull($address->getAddressLine3());
        $this->assertEquals('Illapel', $address->getMunicipal());
        $this->assertEquals('Choapa', $address->getCity());
        $this->assertEquals('Coquimbo', $address->getState());
        $this->assertEquals('1930000', $address->getPostCode());
        $this->assertEquals('CL', $address->getCountryCode());
        $this->assertEquals('test@falabella.cl', $address->getEmail());
        $this->assertEquals('nametest', $address->getName());
        $this->assertNull($address->getContacts());
        $this->assertEquals('Chile', $address->getCountry());
        $this->assertEquals('04201', $address->getContactAddress2Code());
    }
}
