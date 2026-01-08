<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\Contact;
use SimpleXMLElement;

class ContactFactory
{
    public static function make(SimpleXMLElement $xml): Contact
    {
        $type = (string) $xml->type ?: null;
        $value = (string) $xml->value ?: null;
        $typeDescription = (string) $xml->typeDescription ?: null;

        return new Contact($type, $value, $typeDescription);
    }
}
