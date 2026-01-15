<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\Contacts;
use SimpleXMLElement;

class ContactsFactory
{
    public static function make(SimpleXMLElement $xml): Contacts
    {
        $addressContacts = new Contacts();

        foreach ($xml->contacts as $contact) {
            $addressContacts->add(ContactFactory::make($contact));
        }

        return $addressContacts;
    }
}
