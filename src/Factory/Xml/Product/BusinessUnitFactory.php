<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use DateTimeImmutable;
use Linio\SellerCenter\Model\Product\BusinessUnit;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class BusinessUnitFactory
{
    private const XML_MODEL = 'BusinessUnit';
    private const REQUIRED_FIELDS = [
        'OperatorCode',
        'Price',
        'Stock',
        'Status',
        'IsPublished',
    ];

    public static function make(SimpleXMLElement $element): BusinessUnit
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

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
