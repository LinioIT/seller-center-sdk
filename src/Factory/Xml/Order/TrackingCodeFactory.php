<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\TrackingCode;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class TrackingCodeFactory
{
    private const XML_MODEL = 'TrackingCode';
    private const REQUIRED_FIELDS = [
        'DispatchId',
        'TrackingNumber',
    ];

    public static function make(SimpleXMLElement $element): TrackingCode
    {
        XmlStructureValidator::validateStructure($element->TrackingCode, self::XML_MODEL, self::REQUIRED_FIELDS);

        return new TrackingCode(
            (string) $element->TrackingCode->DispatchId,
            (string) $element->TrackingCode->TrackingNumber
        );
    }
}
