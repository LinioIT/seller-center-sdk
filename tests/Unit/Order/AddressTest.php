<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\AddressFactory;
use Linio\SellerCenter\Model\Order\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testItReturnsValidAddress(): void
    {
        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        $address = AddressFactory::make($simpleXml);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($simpleXml->FirstName, $address->getFirstName());
        $this->assertEquals($simpleXml->LastName, $address->getLastName());
        $this->assertEquals((int) $simpleXml->Phone, $address->getPhone());
        $this->assertEquals((int) $simpleXml->Phone2, $address->getPhone2());
        $this->assertEquals($simpleXml->Address1, $address->getAddress());
        $this->assertEquals($simpleXml->CustomerEmail, $address->getCustomerEmail());
        $this->assertEquals($simpleXml->City, $address->getCity());
        $this->assertEquals($simpleXml->Ward, $address->getWard());
        $this->assertEquals($simpleXml->Region, $address->getRegion());
        $this->assertEquals($simpleXml->PostCode, $address->getPostCode());
        $this->assertEquals($simpleXml->Country, $address->getCountry());
    }

    public function testItThrowsAExceptionWithoutAFirstNameInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property FirstName should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutALastNameInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property LastName should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPhoneInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property Phone should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPhone2InTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property Phone2 should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAAddressInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property Address should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutACustomerEmailInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property CustomerEmail should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutACityInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property City should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAWardInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property Ward should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutARegionInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property Region should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <PostCode>12345</PostCode>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPostCodeInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property PostCode should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <Country>Germany</Country>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutACountryInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Address. The property Country should exist.');

        $simpleXml = simplexml_load_string(
            '<Address>
                      <FirstName>John</FirstName>
                      <LastName>Doe</LastName>
                      <Phone>0123456789</Phone>
                      <Phone2></Phone2>
                      <Address1>testtestcarmen</Address1>
                      <CustomerEmail>hello@sellercenter.net</CustomerEmail>
                      <City>Kuala Lumpur</City>
                      <Ward>test</Ward>
                      <Region>Berlin</Region>
                      <PostCode>12345</PostCode>
                 </Address>'
        );

        AddressFactory::make($simpleXml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $phone = 123456789;
        $address1 = 'address';
        $customerEmail = 'hello@sellercenter.net';
        $city = 'City';
        $ward = 'test';
        $region = 'Region';
        $postCode = '12345';
        $country = 'country';

        $xml = sprintf(
            '<Address>
                      <FirstName>%s</FirstName>
                      <LastName>%s</LastName>
                      <Phone>%s</Phone>
                      <Phone2></Phone2>
                      <Address1>%s</Address1>
                      <CustomerEmail>%s</CustomerEmail>
                      <City>%s</City>
                      <Ward>%s</Ward>
                      <Region>%s</Region>
                      <PostCode>%s</PostCode>
                      <Country>%s</Country>
                 </Address>',
            $firstName,
            $lastName,
            $phone,
            $address1,
            $customerEmail,
            $city,
            $ward,
            $region,
            $postCode,
            $country
        );

        $simpleXml = simplexml_load_string($xml);

        $address = AddressFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"firstName": "%s", "lastName": "%s", "phone": %d, "phone2": null, "address": "%s", "customerEmail": "%s", "city": "%s", "ward": "%s", "region": "%s", "postCode": "%s", "country": "%s"}',
            $firstName,
            $lastName,
            $phone,
            $address1,
            $customerEmail,
            $city,
            $ward,
            $region,
            $postCode,
            $country
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($address));
    }
}
