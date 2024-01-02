<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Order\Address;
use SimpleXMLElement;

class AddressFactory
{
    public static function make(SimpleXMLElement $element): Address
    {
        if (!property_exists($element, 'FirstName')) {
            throw new InvalidXmlStructureException('Address', 'FirstName');
        }

        if (!property_exists($element, 'LastName')) {
            throw new InvalidXmlStructureException('Address', 'LastName');
        }

        if (!property_exists($element, 'Phone')) {
            throw new InvalidXmlStructureException('Address', 'Phone');
        }

        if (!property_exists($element, 'Phone2')) {
            throw new InvalidXmlStructureException('Address', 'Phone2');
        }

        if (!property_exists($element, 'Address1')) {
            throw new InvalidXmlStructureException('Address', 'Address');
        }

        if (!property_exists($element, 'Address2')) {
            $address2 = null;
        } else {
            $address2 = $element->Address2;
        }

        if (!property_exists($element, 'CustomerEmail')) {
            throw new InvalidXmlStructureException('Address', 'CustomerEmail');
        }

        if (!property_exists($element, 'City')) {
            throw new InvalidXmlStructureException('Address', 'City');
        }

        if (!property_exists($element, 'Ward')) {
            throw new InvalidXmlStructureException('Address', 'Ward');
        }

        if (!property_exists($element, 'Region')) {
            throw new InvalidXmlStructureException('Address', 'Region');
        }

        if (!property_exists($element, 'PostCode')) {
            throw new InvalidXmlStructureException('Address', 'PostCode');
        }

        if (!property_exists($element, 'Country')) {
            throw new InvalidXmlStructureException('Address', 'Country');
        }

        return new Address(
            (string) $element->FirstName,
            (string) $element->LastName,
            (int) $element->Phone,
            (int) $element->Phone2,
            (string) $element->Address1,
            (string) $address2,
            (string) $element->CustomerEmail,
            (string) $element->City,
            (string) $element->Ward,
            (string) $element->Region,
            (string) $element->PostCode,
            (string) $element->Country
        );
    }
}
