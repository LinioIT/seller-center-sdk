<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\QualityControl;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\QualityControl\QualityControl;
use SimpleXMLElement;

class QualityControlFactory
{
    public static function make(SimpleXMLElement $element): QualityControl
    {
        if (!property_exists($element, 'SellerSKU')) {
            throw new InvalidXmlStructureException('QcStatus', 'SellerSku');
        }

        if (!property_exists($element, 'Status')) {
            throw new InvalidXmlStructureException('QcStatus', 'Status');
        }

        $dataChanged = null;

        if (property_exists($element, 'DataChanged')) {
            $dataChanged = !empty($element->DataChanged);
        }

        return new QualityControl(
            (string) $element->SellerSKU,
            (string) $element->Status,
            $dataChanged,
            (string) $element->Reason
        );
    }
}
