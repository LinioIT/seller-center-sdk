<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\ExtraBillingAttributes;
use SimpleXMLElement;

class ExtraBillingAttributesFactory
{
    public static function make(SimpleXMLElement $element): ExtraBillingAttributes
    {
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
