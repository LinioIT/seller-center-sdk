<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Validator;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use SimpleXMLElement;

class XmlStructureValidator
{
    public static function validateStructure(SimpleXMLElement $xml, string $model, array $properties): void
    {
        foreach ($properties as $property) {
            if (!property_exists($xml, $property)) {
                throw new InvalidXmlStructureException($model, $property);
            }
        }
    }
}
