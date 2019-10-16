<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\CategoryAttributeOption;
use SimpleXMLElement;

class CategoryAttributeOptionFactory
{
    public static function make(SimpleXMLElement $xml): CategoryAttributeOption
    {
        if (!property_exists($xml, 'GlobalIdentifier')) {
            throw new InvalidXmlStructureException('Option', 'GlobalIdentifier');
        }

        if (!property_exists($xml, 'Name')) {
            throw new InvalidXmlStructureException('Option', 'Name');
        }

        if (!property_exists($xml, 'isDefault')) {
            throw new InvalidXmlStructureException('Option', 'isDefault');
        }

        $default = !empty($xml->isDefault);

        return new CategoryAttributeOption((string) $xml->GlobalIdentifier, (string) $xml->Name, $default);
    }
}
