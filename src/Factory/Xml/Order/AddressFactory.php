<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\Address;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class AddressFactory
{
    private const XML_MODEL = 'Address';
    private const REQUIRED_FIELDS = [
        'FirstName',
        'LastName',
        'Phone',
        'Phone2',
        'Address1',
        'CustomerEmail',
        'City',
        'Ward',
        'Region',
        'PostCode',
        'Country',
    ];

    public static function make(SimpleXMLElement $element): Address
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

        return new Address(
            (string) $element->FirstName,
            (string) $element->LastName,
            (int) $element->Phone,
            (int) $element->Phone2,
            (string) $element->Address1,
            (string) $element->CustomerEmail,
            (string) $element->City,
            (string) $element->Ward,
            (string) $element->Region,
            (string) $element->PostCode,
            (string) $element->Country
        );
    }
}
