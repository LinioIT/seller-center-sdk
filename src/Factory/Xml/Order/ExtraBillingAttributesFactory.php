<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\ExtraBillingAttributes;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class ExtraBillingAttributesFactory
{
    private const XML_MODEL = 'BillingAttributes';
    private const REQUIRED_FIELDS = [
        'LegalId',
        'FiscalPerson',
        'DocumentType',
        'ReceiverRegion',
        'ReceiverAddress',
        'ReceiverPostcode',
        'ReceiverLegalName',
        'ReceiverMunicipality',
        'ReceiverTypeRegimen',
        'CustomerVerifierDigit',
        'ReceiverLocality',
        'ReceiverEmail',
        'ReceiverPhonenumber',
    ];

    public static function make(SimpleXMLElement $element): ExtraBillingAttributes
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

        return new ExtraBillingAttributes(
            (string) $element->LegalId,
            (string) $element->FiscalPerson,
            (string) $element->DocumentType,
            (string) $element->ReceiverRegion,
            (string) $element->ReceiverAddress,
            (string) $element->ReceiverPostcode,
            (string) $element->ReceiverLegalName,
            (string) $element->ReceiverMunicipality,
            (string) $element->ReceiverTypeRegimen,
            (string) $element->CustomerVerifierDigit,
            (string) $element->ReceiverLocality,
            (string) $element->ReceiverEmail,
            (string) $element->ReceiverPhonenumber
        );
    }
}
