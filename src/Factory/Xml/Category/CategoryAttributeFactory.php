<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\CategoryAttribute;
use SimpleXMLElement;

class CategoryAttributeFactory
{
    public static function make(SimpleXMLElement $element): CategoryAttribute
    {
        if (!property_exists($element, 'Label')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'Label');
        }

        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'Name');
        }

        if (!property_exists($element, 'FeedName')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'FeedName');
        }

        if (!property_exists($element, 'GlobalIdentifier')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'GlobalIdentifier');
        }

        if (!property_exists($element, 'GroupName')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'GroupName');
        }

        if (!property_exists($element, 'isMandatory')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'isMandatory');
        }

        if (!property_exists($element, 'IsGlobalAttribute')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'IsGlobalAttribute');
        }

        if (!property_exists($element, 'Description')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'Description');
        }

        if (!property_exists($element, 'ProductType')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'ProductType');
        }

        if (!property_exists($element, 'InputType')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'InputType');
        }

        if (!property_exists($element, 'AttributeType')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'AttributeType');
        }

        if (!property_exists($element, 'ExampleValue')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'ExampleValue');
        }

        if (!property_exists($element, 'MaxLength')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'MaxLength');
        }

        if (!property_exists($element, 'Options')) {
            throw new InvalidXmlStructureException('CategoryAttribute', 'Options');
        }

        // Add Support to 0 as false when the isMandatory field is zero as a string.
        $mandatory = !empty($element->isMandatory);
        $globalAttribute = !empty($element->IsGlobalAttribute);

        $options = CategoryAttributeOptionsFactory::make($element->Options);

        return new CategoryAttribute(
            (string) $element->Name,
            (string) $element->FeedName,
            (string) $element->Label,
            (string) $element->GlobalIdentifier,
            (string) $element->AttributeType,
            $options,
            $mandatory,
            $globalAttribute,
            (string) $element->Description,
            (string) $element->ProductType,
            (string) $element->InputType,
            (string) $element->GroupName,
            (int) $element->MaxLength,
            (string) $element->ExampleValue
        );
    }
}
