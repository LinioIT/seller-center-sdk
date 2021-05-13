<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use SimpleXMLElement;

class BusinessUnitFactory
{
    public static function make(SimpleXMLElement $element): BusinessUnit
    {
        if (!property_exists($element, 'OperatorCode')) {
            throw new InvalidXmlStructureException('BusinessUnit', 'OperatorCode');
        }

        if (!property_exists($element, 'Price')) {
            throw new InvalidXmlStructureException('BusinessUnit', 'Price');
        }

        if (!property_exists($element, 'Stock')) {
            throw new InvalidXmlStructureException('BusinessUnit', 'Stock');
        }

        if (!property_exists($element, 'Status')) {
            throw new InvalidXmlStructureException('BusinessUnit', 'Status');
        }

        if (!property_exists($element, 'IsPublished')) {
            throw new InvalidXmlStructureException('BusinessUnit', 'IsPublished');
        }

        $businessUnit = new BusinessUnit(
            (string) $element->OperatorCode,
            (float) $element->Price,
            (int) $element->Stock,
            (string) $element->Status,
            (int) $element->IsPublished
        );

        if (!empty($element->BusinessUnit)) {
            $businessUnit->setBusinessUnit((string) $element->BusinessUnit);
        }

        if (!empty($element->SpecialPrice)) {
            $businessUnit->setSalePrice((float) $element->SpecialPrice);
        }

        if (!empty($element->SpecialPrice)) {
            $businessUnit->setSalePrice((float) $element->SpecialPrice);
        }

        if (!empty($element->SpecialFromDate)) {
            $saleStartDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->SpecialFromDate);

            if ($saleStartDate) {
                $businessUnit->setSaleStartDate($saleStartDate);
            }
        }

        if (!empty($element->SpecialToDate)) {
            $saleEndDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->SpecialToDate);

            if ($saleEndDate) {
                $businessUnit->setSaleEndDate($saleEndDate);
            }
        }

        return $businessUnit;
    }
}
