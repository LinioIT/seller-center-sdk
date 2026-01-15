<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\WarehouseAddress;
use SimpleXMLElement;

class WarehouseAddressFactory
{
    public static function make(SimpleXMLElement $xml): WarehouseAddress
    {
        $addressLine1 = (string) $xml->addressLine1 ?: null;
        $addressLine2 = (string) $xml->addressLine2 ?: null;
        $addressLine3 = (string) $xml->addressLine3 ?: null;
        $postCode = (string) $xml->postcode ?: null;
        $email = (string) $xml->email ?: null;
        $name = (string) $xml->name ?: null;
        $contactAddress2Code = (string) $xml->contactAddress2Code ?: null;

        $city = (string) $xml->city;
        $state = (string) $xml->state;
        $municipal = (string) $xml->municipal;
        $country = (string) $xml->country;
        $countryCode = (string) $xml->countryCode;
        $addressContacts = !empty($xml->contacts) ? ContactsFactory::make($xml->contacts) : null;

        return new WarehouseAddress(
            $addressLine1,
            $addressLine2,
            $addressLine3,
            $municipal,
            $city,
            $state,
            $postCode,
            $countryCode,
            $country,
            $email,
            $name,
            $contactAddress2Code,
            $addressContacts
        );
    }
}
