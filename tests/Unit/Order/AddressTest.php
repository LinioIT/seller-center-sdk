<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\AddressFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\Address;

class AddressTest extends LinioTestCase
{
    protected $firstName = 'John';
    protected $lastName = 'Doe';
    protected $phone = 123456789;
    protected $address1 = 'address';
    protected $customerEmail = 'hello@sellercenter.net';
    protected $city = 'City';
    protected $ward = 'test';
    protected $region = 'Region';
    protected $postCode = '12345';
    protected $country = 'country';

    public function testItReturnsValidAddress(): void
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForAddress());

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

    /**
     * @dataProvider invalidXmlStructure
     */
    public function testItThrowsAExceptionWithoutAPropertyInTheXml(string $property): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a Address. The property %s should exist.',
                $property
            )
        );

        $simpleXml = simplexml_load_string($this->createXmlStringForAddress());
        unset($simpleXml->{$property});

        AddressFactory::make($simpleXml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $xml = $this->createXmlStringForAddress();

        $simpleXml = simplexml_load_string($xml);

        $address = AddressFactory::make($simpleXml);

        $expectedJson = Json::decode($this->getSchema('Order/Address.json'));
        $expectedJson['firstName'] = $this->firstName;
        $expectedJson['lastName'] = $this->lastName;
        $expectedJson['phone'] = $this->phone;
        $expectedJson['address'] = $this->address1;
        $expectedJson['customerEmail'] = $this->customerEmail;
        $expectedJson['city'] = $this->city;
        $expectedJson['ward'] = $this->ward;
        $expectedJson['region'] = $this->region;
        $expectedJson['postCode'] = $this->postCode;
        $expectedJson['country'] = $this->country;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($address));
    }

    public function createXmlStringForAddress(string $schema = 'Order/Address.xml'): string
    {
        return sprintf(
            $this->getSchema($schema),
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->address1,
            $this->customerEmail,
            $this->city,
            $this->ward,
            $this->region,
            $this->postCode,
            $this->country
        );
    }

    public function invalidXmlStructure(): array
    {
        return [
            ['FirstName'],
            ['LastName'],
            ['Phone'],
            ['Phone2'],
            ['Address1'],
            ['CustomerEmail'],
            ['City'],
            ['Ward'],
            ['Region'],
            ['PostCode'],
            ['Country'],
        ];
    }
}
