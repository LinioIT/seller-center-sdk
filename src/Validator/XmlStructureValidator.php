<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Validator;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use SimpleXMLElement;

class XmlStructureValidator
{
    /**
     * @param string[] $properties
     */
    public static function validateStructure(SimpleXMLElement $xml, string $model, array $properties): void
    {
        foreach ($properties as $property) {
            self::validateProperty($xml, $model, $property);
        }
    }

    public static function validateProperty(SimpleXMLElement $xml, string $model, string $property): void
    {
        if (!property_exists($xml, $property)) {
            throw new InvalidXmlStructureException($model, $property);
        }
    }
}
