<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\ShiftHours;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class ShiftHoursFactory
{
    const XML_MODEL = 'ShiftHours';
    private const REQUIRED_FIELDS = [
        'openingHour',
        'openingHour',
    ];

    public static function make(SimpleXMLElement $xml): ShiftHours
    {
        XmlStructureValidator::validateStructure($xml, self::XML_MODEL, self::REQUIRED_FIELDS);

        $openingHour = (string) $xml->openingHour;
        $closingHour = (string) $xml->closingHour;

        return new ShiftHours($openingHour, $closingHour);
    }
}
