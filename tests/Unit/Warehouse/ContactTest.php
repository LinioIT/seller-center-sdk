<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Warehouse\ContactsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Warehouse\Contact;
use Linio\SellerCenter\Model\Warehouse\Contacts;

class ContactTest extends LinioTestCase
{
    public function testItReturnsAJsonRepresentation(): void
    {
        $contact = new Contact('Phone', '999999999', 'Default warehouse phone number');

        $expectedResult = [
            'type' => 'Phone',
            'value' => '999999999',
            'typeDescription' => 'Default warehouse phone number',
        ];

        $this->assertSame($expectedResult, Json::decode(Json::encode($contact)));
    }

    public function testItReturnsAValidContactValues(): void
    {
        $xml = '
            <contacts>
                <contacts>
                    <type>Phone</type>
                    <value>999999999</value>
                    <typeDescription>phone number</typeDescription>
                </contacts>
            </contacts>
        ';

        $xml = simplexml_load_string($xml);
        $contacts = ContactsFactory::make($xml);

        $this->assertIsArray($contacts->all());
        $this->assertInstanceOf(Contacts::class, $contacts);

        /**
         * @var Contact $contact
         */
        foreach ($contacts->all() as $contact) {
            $this->assertEquals('Phone', $contact->getType());
            $this->assertEquals('999999999', $contact->getValue());
            $this->assertEquals('phone number', $contact->getTypeDescription());
        }
    }
}
