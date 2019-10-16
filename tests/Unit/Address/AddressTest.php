<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\AddressFactory;
use Linio\SellerCenter\Model\Order\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testItReturnsValidAddress(): void
    {
        $simplexml = simplexml_load_string(
            '<Address>
                             <FirstName>Labs</FirstName>
                             <LastName>Rocket</LastName>
                             <Phone/>
                             <Phone2/>
                             <Address1>Johannisstr. 20</Address1>
                             <Address2/>
                             <Address3/>
                             <Address4/>
                             <Address5/>
                             <CustomerEmail/>
                             <City>Berlin</City>
                             <Ward/>
                             <Region/>
                             <PostCode>10117</PostCode>
                             <Country>Germany</Country>
                         </Address>'
        );
        $address = AddressFactory::make($simplexml);
        $this->assertInstanceOf(Address::class, $address);

        $this->assertEquals('Labs', $address->getFirstName());
        $this->assertEquals('Rocket', $address->getLastName());
        $this->assertEmpty($address->getPhone());
        $this->assertEmpty($address->getPhone2());
        $this->assertEquals('Johannisstr. 20', $address->getAddress());
        $this->assertEmpty($address->getCustomerEmail());
        $this->assertEquals('Berlin', $address->getCity());
        $this->assertEmpty($address->getWard());
        $this->assertEmpty($address->getRegion());
        $this->assertEquals(10117, $address->getPostCode());
        $this->assertEquals('Germany', $address->getCountry());
    }

    public function testItThrowsExceptionIfParameterIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property FirstName should exist.');

        $simplexml = simplexml_load_string(
            '<Address>
                             <LastName>Rocket</LastName>
                             <Phone/>
                             <Phone2/>
                             <Address1>Johannisstr. 20</Address1>
                             <Address2/>
                             <Address3/>
                             <Address4/>
                             <Address5/>
                             <CustomerEmail/>
                             <City>Berlin</City>
                             <Ward/>
                             <Region/>
                             <PostCode>10117</PostCode>
                             <Country>Germany</Country>
                         </Address>'
        );
        AddressFactory::make($simplexml);
    }
}
